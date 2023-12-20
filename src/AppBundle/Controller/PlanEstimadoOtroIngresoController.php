<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoOtroIngresoController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanOtrosIngresosDivision", name="gestionarEstimadoPlanOtrosIngresosDivision")
     */
    public function gestionarEstimadoPlanOtrosIngresosDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoOtroIngreso = $session->get('totalEstimadoOtroIngreso');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionOtroIngreso = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->totalEstimadoDivisionOtroIngreso($planEstimado);
        $cantidadEstimadoDivisionOtroIngreso = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->cantidadEstimadoDivisionOtroIngreso($planEstimado);
        $cantidadEstimadoDivisionMesOtroIngreso = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->cantidadEstimadoDivisionMesOtroIngreso($planEstimado);

        if (empty($totalEstimadoDivisionOtroIngreso)) {
            $totalEstimadoDivisionOtroIngreso = 0;
            $aprobarEstimadoOtroIngresoDivision = false;
            $porcientoDivisionMensual = 0;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesOtroIngreso / $cantidadEstimadoDivisionOtroIngreso) * 100);
            $aprobarEstimadoOtroIngresoDivision = $activo[0]->getAprobarPrespuestoDivisionOtroIngreso();
        }

        $graficosTotalesEstimadosDivisionesOtrosIngresos = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->graficoTotalesEstimadosDivisionesOtroIngreso($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Otros Ingresos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de otros ingresos por división'
        ));

        return $this->render('GestionPlanEstimado/GestionOtrosIngresos/gestionOtroIngresoDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoOtroIngreso' => $totalEstimadoOtroIngreso,
            'totalEstimadoDivisionOtroIngreso' => $totalEstimadoDivisionOtroIngreso,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoOtroIngresoDivision' => $aprobarEstimadoOtroIngresoDivision,
            'graficosTotalesEstimadosDivisionesOtrosIngresos' => $graficosTotalesEstimadosDivisionesOtrosIngresos,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanOtrosIngresosCentroCosto", name="gestionarEstimadoPlanOtrosIngresosCentroCosto")
     */
    public function gestionarEstimadoPlanOtrosIngresosCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoOtroIngreso = $session->get('totalEstimadoOtroIngresoDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->planOtroIngresoDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->verificarAprobadoEstimadoOtroIngresoCentroCostoMes($planEstimado);

        $graficosOtroIngresoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->graficosOtroIngresoEstimadoDivisionMensualTodos($planEstimado);

        $graficosOtroIngresoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->graficosOtroIngresoEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Otros Ingresos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de otros ingresos por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionOtrosIngresos/gestionOtroIngresoCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoOtroIngreso' => $totalEstimadoOtroIngreso,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosOtroIngresoEstimadoDivisionMensual' => $graficosOtroIngresoEstimadoDivisionMensual,
            'graficosOtroIngresoEstimadoCentroCostoMensual' => $graficosOtroIngresoEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/insertTotalOtroIngreso", name="insertTotalOtroIngreso")
     */
    public function insertTotalOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalOtroIngreso($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Otros Ingresos',
            'descripcion' => 'Se insertó el total de otros ingresos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoOtroIngreso', $resp->getTotalOtroIngreso());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalOtroIngreso", name="updateTotalOtroIngreso")
     */
    public function updateTotalOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalOtroIngreso($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Otros Ingresos',
            'descripcion' => 'Se modificó el total de otros ingresos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoOtroIngreso', $resp->getTotalOtroIngreso());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalOtroIngreso", name="aprobarTotalOtroIngreso")
     */
    public function aprobarTotalOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalOtroIngreso($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de Otros Ingresos',
            'descripcion' => 'Se aprobó el presupuesto de otros ingresos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoOtroIngreso', $resp->getAprobarPrespuestoTotalOtroIngreso());
        return new Response('ok');
    }

    /**
     * @Route("/graficoOtroIngresoEstimadoDivisionMensual", name="graficoOtroIngresoEstimadoDivisionMensual")
     */
    public function graficoOtroIngresoEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosOtroIngresoEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->graficosOtroIngresoEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosOtroIngresoEstimadoDivisionMensual)) {
            return new Response($graficosOtroIngresoEstimadoDivisionMensual);
        }

        $result = json_encode($graficosOtroIngresoEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionOtroIngreso", name="insertTotalEstimadoDivisionOtroIngreso")
     */
    public function insertTotalEstimadoDivisionOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->agregarTotalEstimadoOtroIngresoDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto de otros ingresos para una división',
            'descripcion' => 'Se asignó el presupuesto de otros ingresos a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionOtroIngreso", name="updateTotalEstimadoDivisionOtroIngreso")
     */
    public function updateTotalEstimadoDivisionOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->modificarTotalEstimadoOtroIngresoDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto de otros ingresos para una división',
            'descripcion' => 'Se modificó el presupuesto de otros ingresos a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionOtroIngreso", name="aprobarPresupuestoEstimadoDivisionOtroIngreso")
     */
    public function aprobarPresupuestoEstimadoDivisionOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionOtroIngreso($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de otros ingresos',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de otros ingresos del año: '.$resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoOtroIngresoDivisionMes", name="insertTotalEstimadoOtroIngresoDivisionMes")
     */
    public function insertTotalEstimadoOtroIngresoDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->masterAgregarEstimadoOtroIngresoDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->verificarAprobadoEstimadoOtroIngresoDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesOtroIngreso($data);
            $session = new Session();
            $session->set('aprobarEstimadoOtroIngresoDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoOtroIngresoEstimadoCentroCostoMensual", name="graficoOtroIngresoEstimadoCentroCostoMensual")
     */
    public function graficoOtroIngresoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosOtroIngresoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->graficosOtroIngresoEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosOtroIngresoEstimadoCentroCostoMensual)) {
            return new Response($graficosOtroIngresoEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosOtroIngresoEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoOtroIngreso", name="insertTotalEstimadoCentroCostoOtroIngreso")
     */
    public function insertTotalEstimadoCentroCostoOtroIngresoAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->masterAgregarEstimadoOtroIngresoCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoOtroIngreso", name="updateTotalEstimadoCentroCostoOtroIngreso")
     */
    public function updateTotalEstimadoCentroCostoOtroIngresoAction()
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

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->masterModificadorEstimadoOtroIngresoCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoOtroIngreso", name="aprobarPresupuestoEstimadoCentroCostoOtroIngreso")
     */
    public function aprobarPresupuestoEstimadoCentroCostoOtroIngresoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->aprobarTotalEstimadoCentroCostoOtroIngreso($data);

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
        $cantDivisiones  = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->planOtroIngresoCantidadDivisiones($data);

        $cantMensuales = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->verificarAprobadoEstimadoOtroIngresoCentroCostoMeses($data);

        $verificar = $cantDivisiones - $cantMensuales;

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesOtroIngreso($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/dashboardOtroIngresoEstimadoDivisionMensual", name="dashboardOtroIngresoEstimadoDivisionMensual")
     */
    public function dashboardOtroIngresoEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosOtroIngresoEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->graficosOtroIngresoEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosOtrosIngresos = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->graficoTotalesEstimadosCentroCostosOtrosIngresos($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosOtroIngresoEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosOtrosIngresos,
        );

        if (is_string($graficosOtroIngresoEstimadoDivisionMensual)) {
            return new Response($graficosOtroIngresoEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardOtroIngresoEstimadoCentroCostoMensual", name="dashboardOtroIngresoEstimadoCentroCostoMensual")
     */
    public function dashboardOtroIngresoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosOtrosIngresos = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->graficosOtroIngresoEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosOtrosIngresos)) {
            return new Response($graficosTotalesEstimadosCentroCostosOtrosIngresos);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosOtrosIngresos);
        return new JsonResponse($result);

    }

}
