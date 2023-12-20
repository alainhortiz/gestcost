<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoBonificacionController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanBonificacionesDivision", name="gestionarEstimadoPlanBonificacionesDivision")
     */
    public function gestionarEstimadoPlanBonificacionesDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoBonificacion = $session->get('totalEstimadoBonificacion');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionBonificacion = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->totalEstimadoDivisionBonificacion($planEstimado);
        $cantidadEstimadoDivisionBonificacion = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->cantidadEstimadoDivisionBonificacion($planEstimado);
        $cantidadEstimadoDivisionMesBonificacion = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->cantidadEstimadoDivisionMesBonificacion($planEstimado);

        if (empty($totalEstimadoDivisionBonificacion)) {
            $totalEstimadoDivisionBonificacion = 0;
            $aprobarEstimadoBonificacionDivision = false;
            $porcientoDivisionMensual = 0;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesBonificacion / $cantidadEstimadoDivisionBonificacion) * 100);
            $aprobarEstimadoBonificacionDivision = $activo[0]->getAprobarPrespuestoDivisionBonificacion();
        }

        $graficosTotalesEstimadosDivisionesBonificaciones = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->graficoTotalesEstimadosDivisionesBonificacion($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Bonificación - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Bonificación por división'
        ));

        return $this->render('GestionPlanEstimado/GestionBonificacion/gestionBonificacionDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoBonificacion' => $totalEstimadoBonificacion,
            'totalEstimadoDivisionBonificacion' => $totalEstimadoDivisionBonificacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoBonificacionDivision' => $aprobarEstimadoBonificacionDivision,
            'graficosTotalesEstimadosDivisionesBonificaciones' => $graficosTotalesEstimadosDivisionesBonificaciones,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanBonificacionesCentroCosto", name="gestionarEstimadoPlanBonificacionesCentroCosto")
     */
    public function gestionarEstimadoPlanBonificacionesCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoBonificacion = $session->get('totalEstimadoBonificacionDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->planBonificacionDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->verificarAprobadoEstimadoBonificacionCentroCostoMes($planEstimado);

        $graficosBonificacionEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->graficosBonificacionEstimadoDivisionMensualTodos($planEstimado);

        $graficosBonificacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->graficosBonificacionEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Bonificación - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Bonificación por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionBonificacion/gestionBonificacionCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoBonificacion' => $totalEstimadoBonificacion,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosBonificacionEstimadoDivisionMensual' => $graficosBonificacionEstimadoDivisionMensual,
            'graficosBonificacionEstimadoCentroCostoMensual' => $graficosBonificacionEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/graficoBonificacionEstimadoDivisionMensual", name="graficoBonificacionEstimadoDivisionMensual")
     */
    public function graficoBonificacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosBonificacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->graficosBonificacionEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosBonificacionEstimadoDivisionMensual)) {
            return new Response($graficosBonificacionEstimadoDivisionMensual);
        }

        $result = json_encode($graficosBonificacionEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionBonificacion", name="insertTotalEstimadoDivisionBonificacion")
     */
    public function insertTotalEstimadoDivisionBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->agregarTotalEstimadoBonificacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto estimado de bonificación para una división',
            'descripcion' => 'Se asignó el presupuesto estimado de bonificación a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionBonificacion", name="updateTotalEstimadoDivisionBonificacion")
     */
    public function updateTotalEstimadoDivisionBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->modificarTotalEstimadoBonificacionDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto estimado de bonificación para una división',
            'descripcion' => 'Se modificó el presupuesto estimado de bonificación a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionBonificacion", name="aprobarPresupuestoEstimadoDivisionBonificacion")
     */
    public function aprobarPresupuestoEstimadoDivisionBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionBonificacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de Bonificación',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de Bonificación del año: '.$resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoBonificacionDivisionMes", name="insertTotalEstimadoBonificacionDivisionMes")
     */
    public function insertTotalEstimadoBonificacionDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->masterAgregarEstimadoBonificacionDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->verificarAprobadoEstimadoBonificacionDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesBonificacion($data);
            $session = new Session();
            $session->set('aprobarEstimadoBonificacionDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoBonificacionEstimadoCentroCostoMensual", name="graficoBonificacionEstimadoCentroCostoMensual")
     */
    public function graficoBonificacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosBonificacionEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->graficosBonificacionEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosBonificacionEstimadoCentroCostoMensual)) {
            return new Response($graficosBonificacionEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosBonificacionEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoBonificacion", name="insertTotalEstimadoCentroCostoBonificacion")
     */
    public function insertTotalEstimadoCentroCostoBonificacionAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->masterAgregarEstimadoBonificacionCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoBonificacion", name="updateTotalEstimadoCentroCostoBonificacion")
     */
    public function updateTotalEstimadoCentroCostoBonificacionAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->masterModificadorEstimadoBonificacionCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoBonificacion", name="aprobarPresupuestoEstimadoCentroCostoBonificacion")
     */
    public function aprobarPresupuestoEstimadoCentroCostoBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->aprobarTotalEstimadoCentroCostoBonificacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el presupuesto mensual estimado por centros de costos de una división',
            'descripcion' => 'Se aprobó el presupuesto mensual estimado por centros de costos para la división: '.$resp->getDivisionCentroCosto()->getNombre()
        ));


        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $cantDivisiones  = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->planBonificacionCantidadDivisiones($data);

        $cantMensuales = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->verificarAprobadoEstimadoBonificacionCentroCostoMeses($data);

        $verificar = $cantDivisiones - $cantMensuales;

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesBonificacion($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/dashboardBonificacionEstimadoDivisionMensual", name="dashboardBonificacionEstimadoDivisionMensual")
     */
    public function dashboardBonificacionEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosBonificacionEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->graficosBonificacionEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosBonificaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->graficoTotalesEstimadosCentroCostosBonificaciones($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosBonificacionEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosBonificaciones,
        );

        if (is_string($graficosBonificacionEstimadoDivisionMensual)) {
            return new Response($graficosBonificacionEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardBonificacionEstimadoCentroCostoMensual", name="dashboardBonificacionEstimadoCentroCostoMensual")
     */
    public function dashboardBonificacionEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosBonificaciones = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->graficosBonificacionEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosBonificaciones)) {
            return new Response($graficosTotalesEstimadosCentroCostosBonificaciones);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosBonificaciones);
        return new JsonResponse($result);

    }
}
