<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoDepreciacionController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanDepreciacionesDivision", name="gestionarEstimadoPlanDepreciacionesDivision")
     */
    public function gestionarEstimadoPlanDepreciacionesDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoDepreciacion = $session->get('totalEstimadoDepreciacion');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionDepreciacion = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->totalEstimadoDivisionDepreciacion($planEstimado);
        $cantidadEstimadoDivisionDepreciacion = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->cantidadEstimadoDivisionDepreciacion($planEstimado);
        $cantidadEstimadoDivisionMesDepreciacion = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->cantidadEstimadoDivisionMesDepreciacion($planEstimado);

        if (empty($totalEstimadoDivisionDepreciacion)) {
            $totalEstimadoDivisionDepreciacion = 0;
            $aprobarEstimadoDepreciacionDivision = false;
            $porcientoDivisionMensual = 0;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesDepreciacion / $cantidadEstimadoDivisionDepreciacion) * 100);
            $aprobarEstimadoDepreciacionDivision = $activo[0]->getAprobarPrespuestoDivisionDepreciacion();
        }

        $graficosTotalesEstimadosDivisionesDepreciaciones = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->graficoTotalesEstimadosDivisionesDepreciacion($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Depreciación - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Depreciación por división'
        ));

        return $this->render('GestionPlanEstimado/GestionDepreciacion/gestionDepreciacionDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoDepreciacion' => $totalEstimadoDepreciacion,
            'totalEstimadoDivisionDepreciacion' => $totalEstimadoDivisionDepreciacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoDepreciacionDivision' => $aprobarEstimadoDepreciacionDivision,
            'graficosTotalesEstimadosDivisionesDepreciaciones' => $graficosTotalesEstimadosDivisionesDepreciaciones,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanDepreciacionesCentroCosto", name="gestionarEstimadoPlanDepreciacionesCentroCosto")
     */
    public function gestionarEstimadoPlanDepreciacionesCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoDepreciacion = $session->get('totalEstimadoDepreciacionDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->planDepreciacionDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->verificarAprobadoEstimadoDepreciacionCentroCostoMes($planEstimado);

        $graficosDepreciacionEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->graficosDepreciacionEstimadoDivisionMensualTodos($planEstimado);

        $graficosDepreciacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->graficosDepreciacionEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Depreciación - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Depreciación por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionDepreciacion/gestionDepreciacionCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoDepreciacion' => $totalEstimadoDepreciacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosDepreciacionEstimadoDivisionMensual' => $graficosDepreciacionEstimadoDivisionMensual,
            'graficosDepreciacionEstimadoCentroCostoMensual' => $graficosDepreciacionEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/graficoDepreciacionEstimadoDivisionMensual", name="graficoDepreciacionEstimadoDivisionMensual")
     */
    public function graficoDepreciacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosDepreciacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->graficosDepreciacionEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosDepreciacionEstimadoDivisionMensual)) {
            return new Response($graficosDepreciacionEstimadoDivisionMensual);
        }

        $result = json_encode($graficosDepreciacionEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionDepreciacion", name="insertTotalEstimadoDivisionDepreciacion")
     */
    public function insertTotalEstimadoDivisionDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->agregarTotalEstimadoDepreciacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto de depreciación para una división',
            'descripcion' => 'Se asignó el presupuesto de depreciación a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionDepreciacion", name="updateTotalEstimadoDivisionDepreciacion")
     */
    public function updateTotalEstimadoDivisionDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->modificarTotalEstimadoDepreciacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto de depreciación para una división',
            'descripcion' => 'Se modificó el presupuesto de depreciación a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionDepreciacion", name="aprobarPresupuestoEstimadoDivisionDepreciacion")
     */
    public function aprobarPresupuestoEstimadoDivisionDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionDepreciacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de Depreciación',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de Depreciación del año: '.$resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoDepreciacionDivisionMes", name="insertTotalEstimadoDepreciacionDivisionMes")
     */
    public function insertTotalEstimadoDepreciacionDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->masterAgregarEstimadoDepreciacionDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->verificarAprobadoEstimadoDepreciacionDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesDepreciacion($data);
            $session = new Session();
            $session->set('aprobarEstimadoDepreciacionDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoDepreciacionEstimadoCentroCostoMensual", name="graficoDepreciacionEstimadoCentroCostoMensual")
     */
    public function graficoDepreciacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosDepreciacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->graficosDepreciacionEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosDepreciacionEstimadoCentroCostoMensual)) {
            return new Response($graficosDepreciacionEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosDepreciacionEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoDepreciacion", name="insertTotalEstimadoCentroCostoDepreciacion")
     */
    public function insertTotalEstimadoCentroCostoDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->masterAgregarEstimadoDepreciacionCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoDepreciacion", name="updateTotalEstimadoCentroCostoDepreciacion")
     */
    public function updateTotalEstimadoCentroCostoDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->masterModificadorEstimadoDepreciacionCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoDepreciacion", name="aprobarPresupuestoEstimadoCentroCostoDepreciacion")
     */
    public function aprobarPresupuestoEstimadoCentroCostoDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->aprobarTotalEstimadoCentroCostoDepreciacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el presupuesto mensual por centros de costos de una división',
            'descripcion' => 'Se aprobó el presupuesto mensual por centros de costos para la división: '.$resp->getDivisionCentroCosto()->getNombre()
        ));


        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $cantDivisiones  = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->planDepreciacionCantidadDivisiones($data);

        $cantMensuales = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->verificarAprobadoEstimadoDepreciacionCentroCostoMeses($data);

        $verificar = $cantDivisiones - $cantMensuales;

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesDepreciacion($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/dashboardDepreciacionEstimadoDivisionMensual", name="dashboardDepreciacionEstimadoDivisionMensual")
     */
    public function dashboardDepreciacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosDepreciacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->graficosDepreciacionEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosDepreciaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->graficoTotalesEstimadosCentroCostosDepreciaciones($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosDepreciacionEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosDepreciaciones,
        );

        if (is_string($graficosDepreciacionEstimadoDivisionMensual)) return new Response($graficosDepreciacionEstimadoDivisionMensual);
        else {
            $result = json_encode($graficos);
            return new JsonResponse($result);
        }

    }

    /**
     * @Route("/dashboardDepreciacionEstimadoCentroCostoMensual", name="dashboardDepreciacionEstimadoCentroCostoMensual")
     */
    public function dashboardDepreciacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosDepreciaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->graficosDepreciacionEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosDepreciaciones)) {
            return new Response($graficosTotalesEstimadosCentroCostosDepreciaciones);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosDepreciaciones);
        return new JsonResponse($result);

    }

}
