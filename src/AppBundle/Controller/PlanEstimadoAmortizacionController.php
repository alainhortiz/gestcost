<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoAmortizacionController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanAmortizacionesDivision", name="gestionarEstimadoPlanAmortizacionesDivision")
     */
    public function gestionarEstimadoPlanAmortizacionesDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoAmortizacion = $session->get('totalEstimadoAmortizacion');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->totalEstimadoDivisionAmortizacion($planEstimado);
        $cantidadEstimadoDivisionAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->cantidadEstimadoDivisionAmortizacion($planEstimado);
        $cantidadEstimadoDivisionMesAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->cantidadEstimadoDivisionMesAmortizacion($planEstimado);

        if (empty($totalEstimadoDivisionAmortizacion)) {
            $totalEstimadoDivisionAmortizacion = 0;
            $aprobarEstimadoAmortizacionDivision = false;
            $porcientoDivisionMensual = 0;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesAmortizacion / $cantidadEstimadoDivisionAmortizacion) * 100);
            $aprobarEstimadoAmortizacionDivision = $activo[0]->getAprobarPrespuestoDivisionAmortizacion();
        }

        $graficosTotalesEstimadosDivisionesAmortizaciones = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->graficoTotalesEstimadosDivisionesAmortizacion($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Amortización - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Amortización por división'
        ));

        return $this->render('GestionPlanEstimado/GestionAmortizacion/gestionAmortizacionDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoAmortizacion' => $totalEstimadoAmortizacion,
            'totalEstimadoDivisionAmortizacion' => $totalEstimadoDivisionAmortizacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoAmortizacionDivision' => $aprobarEstimadoAmortizacionDivision,
            'graficosTotalesEstimadosDivisionesAmortizaciones' => $graficosTotalesEstimadosDivisionesAmortizaciones,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanAmortizacionesCentroCosto", name="gestionarEstimadoPlanAmortizacionesCentroCosto")
     */
    public function gestionarEstimadoPlanAmortizacionesCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoAmortizacion = $session->get('totalEstimadoAmortizacionDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->planAmortizacionDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->verificarAprobadoEstimadoAmortizacionCentroCostoMes($planEstimado);

        $graficosAmortizacionEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->graficosAmortizacionEstimadoDivisionMensualTodos($planEstimado);

        $graficosAmortizacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->graficosAmortizacionEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Amortización - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Amortización por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionAmortizacion/gestionAmortizacionCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoAmortizacion' => $totalEstimadoAmortizacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosAmortizacionEstimadoDivisionMensual' => $graficosAmortizacionEstimadoDivisionMensual,
            'graficosAmortizacionEstimadoCentroCostoMensual' => $graficosAmortizacionEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/graficoAmortizacionEstimadoDivisionMensual", name="graficoAmortizacionEstimadoDivisionMensual")
     */
    public function graficoAmortizacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosAmortizacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->graficosAmortizacionEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosAmortizacionEstimadoDivisionMensual)) {
            return new Response($graficosAmortizacionEstimadoDivisionMensual);
        }

        $result = json_encode($graficosAmortizacionEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionAmortizacion", name="insertTotalEstimadoDivisionAmortizacion")
     */
    public function insertTotalEstimadoDivisionAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->agregarTotalEstimadoAmortizacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto de amortización para una división',
            'descripcion' => 'Se asignó el presupuesto de amortización a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionAmortizacion", name="updateTotalEstimadoDivisionAmortizacion")
     */
    public function updateTotalEstimadoDivisionAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->modificarTotalEstimadoAmortizacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto de amortización para una división',
            'descripcion' => 'Se modificó el presupuesto de amortización a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionAmortizacion", name="aprobarPresupuestoEstimadoDivisionAmortizacion")
     */
    public function aprobarPresupuestoEstimadoDivisionAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionAmortizacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de Amortización',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de Amortización del año: '.$resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoAmortizacionDivisionMes", name="insertTotalEstimadoAmortizacionDivisionMes")
     */
    public function insertTotalEstimadoAmortizacionDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->masterAgregarEstimadoAmortizacionDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->verificarAprobadoEstimadoAmortizacionDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesAmortizacion($data);
            $session = new Session();
            $session->set('aprobarEstimadoAmortizacionDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoAmortizacionEstimadoCentroCostoMensual", name="graficoAmortizacionEstimadoCentroCostoMensual")
     */
    public function graficoAmortizacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosAmortizacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->graficosAmortizacionEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosAmortizacionEstimadoCentroCostoMensual)) {
            return new Response($graficosAmortizacionEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosAmortizacionEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoAmortizacion", name="insertTotalEstimadoCentroCostoAmortizacion")
     */
    public function insertTotalEstimadoCentroCostoAmortizacionAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->masterAgregarEstimadoAmortizacionCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoAmortizacion", name="updateTotalEstimadoCentroCostoAmortizacion")
     */
    public function updateTotalEstimadoCentroCostoAmortizacionAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->masterModificadorEstimadoAmortizacionCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoAmortizacion", name="aprobarPresupuestoEstimadoCentroCostoAmortizacion")
     */
    public function aprobarPresupuestoEstimadoCentroCostoAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->aprobarTotalEstimadoCentroCostoAmortizacion($data);

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
        $cantDivisiones  = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->planAmortizacionCantidadDivisiones($data);

        $cantMensuales = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->verificarAprobadoEstimadoAmortizacionCentroCostoMeses($data);

        $verificar = $cantDivisiones - $cantMensuales;

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesAmortizacion($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/dashboardAmortizacionEstimadoDivisionMensual", name="dashboardAmortizacionEstimadoDivisionMensual")
     */
    public function dashboardAmortizacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosAmortizacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->graficosAmortizacionEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosAmortizaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->graficoTotalesEstimadosCentroCostosAmortizaciones($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosAmortizacionEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosAmortizaciones,
        );

        if (is_string($graficosAmortizacionEstimadoDivisionMensual)) {
            return new Response($graficosAmortizacionEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardAmortizacionEstimadoCentroCostoMensual", name="dashboardAmortizacionEstimadoCentroCostoMensual")
     */
    public function dashboardAmortizacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosAmortizaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->graficosAmortizacionEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosAmortizaciones)) {
            return new Response($graficosTotalesEstimadosCentroCostosAmortizaciones);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosAmortizaciones);
        return new JsonResponse($result);

    }

}
