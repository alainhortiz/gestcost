<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoComedorController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanComedoresDivision", name="gestionarEstimadoPlanComedoresDivision")
     */
    public function gestionarEstimadoPlanComedoresDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoComedor = $session->get('totalEstimadoComedor');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionComedor = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->totalEstimadoDivisionComedor($planEstimado);
        $cantidadEstimadoDivisionComedor = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->cantidadEstimadoDivisionComedor($planEstimado);
        $cantidadEstimadoDivisionMesComedor = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->cantidadEstimadoDivisionMesComedor($planEstimado);

        if (empty($totalEstimadoDivisionComedor)) {
            $totalEstimadoDivisionComedor = 0;
            $aprobarEstimadoComedorDivision = false;
            $porcientoDivisionMensual = 0;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesComedor / $cantidadEstimadoDivisionComedor) * 100);
            $aprobarEstimadoComedorDivision = $activo[0]->getAprobarPrespuestoDivisionComedor();
        }

        $graficosTotalesEstimadosDivisionesComedores = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->graficoTotalesEstimadosDivisionesComedor($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Gastos de Comedor - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de gastos de comedor por división'
        ));

        return $this->render('GestionPlanEstimado/GestionComedor/gestionComedorDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoComedor' => $totalEstimadoComedor,
            'totalEstimadoDivisionComedor' => $totalEstimadoDivisionComedor,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoComedorDivision' => $aprobarEstimadoComedorDivision,
            'graficosTotalesEstimadosDivisionesComedores' => $graficosTotalesEstimadosDivisionesComedores,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanComedoresCentroCosto", name="gestionarEstimadoPlanComedoresCentroCosto")
     */
    public function gestionarEstimadoPlanComedoresCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoComedor = $session->get('totalEstimadoComedorDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->planComedorDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->verificarAprobadoEstimadoComedorCentroCostoMes($planEstimado);

        $graficosComedorEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->graficosComedorEstimadoDivisionMensualTodos($planEstimado);

        $graficosComedorEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->graficosComedorEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Gastos de comedor - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de gastos de comedor por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionComedor/gestionComedorCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoComedor' => $totalEstimadoComedor,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosComedorEstimadoDivisionMensual' => $graficosComedorEstimadoDivisionMensual,
            'graficosComedorEstimadoCentroCostoMensual' => $graficosComedorEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/graficoComedorEstimadoDivisionMensual", name="graficoComedorEstimadoDivisionMensual")
     */
    public function graficoComedorEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosComedorEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->graficosComedorEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosComedorEstimadoDivisionMensual)) {
            return new Response($graficosComedorEstimadoDivisionMensual);
        }

        $result = json_encode($graficosComedorEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionComedor", name="insertTotalEstimadoDivisionComedor")
     */
    public function insertTotalEstimadoDivisionComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->agregarTotalEstimadoComedorDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto de gastos de comedor para una división',
            'descripcion' => 'Se asignó el presupuesto de gastos de comedor a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionComedor", name="updateTotalEstimadoDivisionComedor")
     */
    public function updateTotalEstimadoDivisionComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->modificarTotalEstimadoComedorDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto de gastos de comedor para una división',
            'descripcion' => 'Se modificó el presupuesto de gastos de comedor a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionComedor", name="aprobarPresupuestoEstimadoDivisionComedor")
     */
    public function aprobarPresupuestoEstimadoDivisionComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionComedor($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de gastos de comedor',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de gastos de comedor del año: '.$resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoComedorDivisionMes", name="insertTotalEstimadoComedorDivisionMes")
     */
    public function insertTotalEstimadoComedorDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->masterAgregarEstimadoComedorDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->verificarAprobadoEstimadoComedorDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesComedor($data);
            $session = new Session();
            $session->set('aprobarEstimadoComedorDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoComedorEstimadoCentroCostoMensual", name="graficoComedorEstimadoCentroCostoMensual")
     */
    public function graficoComedorEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosComedorEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->graficosComedorEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosComedorEstimadoCentroCostoMensual))  return new Response($graficosComedorEstimadoCentroCostoMensual);
        else{
            $result = json_encode($graficosComedorEstimadoCentroCostoMensual);
            return new JsonResponse($result);
        }

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoComedor", name="insertTotalEstimadoCentroCostoComedor")
     */
    public function insertTotalEstimadoCentroCostoComedorAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->masterAgregarEstimadoComedorCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoComedor", name="updateTotalEstimadoCentroCostoComedor")
     */
    public function updateTotalEstimadoCentroCostoComedorAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->masterModificadorEstimadoComedorCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoComedor", name="aprobarPresupuestoEstimadoCentroCostoComedor")
     */
    public function aprobarPresupuestoEstimadoCentroCostoComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->aprobarTotalEstimadoCentroCostoComedor($data);

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
        $cantDivisiones  = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->planComedorCantidadDivisiones($data);

        $cantMensuales = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->verificarAprobadoEstimadoComedorCentroCostoMeses($data);

        $verificar = $cantDivisiones - $cantMensuales;

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesComedor($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/dashboardComedorEstimadoDivisionMensual", name="dashboardComedorEstimadoDivisionMensual")
     */
    public function dashboardComedorEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosComedorEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->graficosComedorEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosComedores = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->graficoTotalesEstimadosCentroCostosComedores($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosComedorEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosComedores,
        );

        if (is_string($graficosComedorEstimadoDivisionMensual)) {
            return new Response($graficosComedorEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardComedorEstimadoCentroCostoMensual", name="dashboardComedorEstimadoCentroCostoMensual")
     */
    public function dashboardComedorEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosComedores = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->graficosComedorEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosComedores)) {
            return new Response($graficosTotalesEstimadosCentroCostosComedores);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosComedores);
        return new JsonResponse($result);

    }

}
