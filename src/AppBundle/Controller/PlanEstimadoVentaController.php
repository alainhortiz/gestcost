<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoVentaController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanVentasDivision", name="gestionarEstimadoPlanVentasDivision")
     */
    public function gestionarEstimadoPlanVentasDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoVenta = $session->get('totalEstimadoVenta');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->totalEstimadoDivisionVenta($planEstimado);
        $cantidadEstimadoDivisionVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->cantidadEstimadoDivisionVenta($planEstimado);
        $cantidadEstimadoDivisionMesVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->cantidadEstimadoDivisionMesVenta($planEstimado);

        if (empty($totalEstimadoDivisionVenta)) {
            $totalEstimadoDivisionVenta = 0;
            $porcientoDivisionMensual = 0;
            $aprobarEstimadoVentaDivision = false;
        } else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesVenta / $cantidadEstimadoDivisionVenta) * 100);
            $aprobarEstimadoVentaDivision = $activo[0]->getAprobarPrespuestoDivisionVenta();
        }

        $graficosTotalesEstimadosDivisionesVentas = $em->getRepository('AppBundle:PlanEstimadoDivision')->graficoTotalesEstimadosDivisionesVentas($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Division Venta - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de ventas por division'
        ));

        return $this->render('GestionPlanEstimado/GestionVentas/gestionVentaDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoVenta' => $totalEstimadoVenta,
            'totalEstimadoDivisionVenta' => $totalEstimadoDivisionVenta,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoVentaDivision' => $aprobarEstimadoVentaDivision,
            'graficosTotalesEstimadosDivisionesVentas' => $graficosTotalesEstimadosDivisionesVentas,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanVentasCentroCosto", name="gestionarEstimadoPlanVentasCentroCosto")
     */
    public function gestionarEstimadoPlanVentasCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $idDivisionCentroCosto = $user->getCentroCosto()->getDivisionCentroCosto()->getId();
        $divisionCentroCosto = $user->getCentroCosto()->getDivisionCentroCosto()->getNombre();
        $centrosCostos = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true, 'divisionCentroCosto' => $idDivisionCentroCosto));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoVenta = $session->get('totalEstimadoVentaDivision');

        $aprobadoEstimadoCentroCostoVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->verificarAprobadoMensualVentaCentroCosto($planEstimado, $idDivisionCentroCosto);

        $totalEstimadoCentroCostoVenta = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->totalEstimadoCentroCostoVenta($planEstimado, $idDivisionCentroCosto);
        $totalEstimadoMesCentroCostoVenta = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->estimadoVentaCentroCostoMensualUnico($planEstimado, $idDivisionCentroCosto);
        $mesesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->estimadoVentaDivisionMensualUnica($planEstimado, $idDivisionCentroCosto);
        $totalPlanMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->progresoVentaEstimadoDivisionMensual($planEstimado, $idDivisionCentroCosto);
        $totalDistribuidoMensual = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->progresoVentaEstimadoCentroCostoMensual($planEstimado, $idDivisionCentroCosto);

        $estadoEnero = 0;
        $estadoFebrero = 0;
        $estadoMarzo = 0;
        $estadoAbril = 0;
        $estadoMayo = 0;
        $estadoJunio = 0;
        $estadoJulio = 0;
        $estadoAgosto = 0;
        $estadoSeptiembre = 0;
        $estadoOctubre = 0;
        $estadoNoviembre = 0;
        $estadoDiciembre = 0;
        $estadoTotal = 0;

        if (!empty($totalDistribuidoMensual)) {
            foreach ($totalDistribuidoMensual as $estado) {
                foreach ($totalPlanMensual as $plan) {
                    if ($estado['mes'] === $plan['mes']) {
                        switch ($estado['mes']) {
                            case 'enero':
                                $estadoEnero = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoEnero;
                                break;
                            case 'febrero':
                                $estadoFebrero = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoFebrero;
                                break;
                            case 'marzo':
                                $estadoMarzo = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoMarzo;
                                break;
                            case 'abril':
                                $estadoAbril = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoAbril;
                                break;
                            case 'mayo':
                                $estadoMayo = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoMayo;
                                break;
                            case 'junio':
                                $estadoJunio = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoJunio;
                                break;
                            case 'julio':
                                $estadoJulio = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoJulio;
                                break;
                            case 'agosto':
                                $estadoAgosto = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoAgosto;
                                break;
                            case 'septiembre':
                                $estadoSeptiembre = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoSeptiembre;
                                break;
                            case 'octubre':
                                $estadoOctubre = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoOctubre;
                                break;
                            case 'noviembre':
                                $estadoNoviembre = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoNoviembre;
                                break;
                            case 'diciembre':
                                $estadoDiciembre = (int)(($estado['presupuestoDistribuido'] / $plan['totalPresupuesto']) * 100);
                                $estadoTotal += $estadoDiciembre;
                                break;
                        }
                    }
                }
            }
        }

        $estadosMeses = array(
            'estadoEnero' => $estadoEnero,
            'estadoFebrero' => $estadoFebrero,
            'estadoMarzo' => $estadoMarzo,
            'estadoAbril' => $estadoAbril,
            'estadoMayo' => $estadoMayo,
            'estadoJunio' => $estadoJunio,
            'estadoJulio' => $estadoJulio,
            'estadoAgosto' => $estadoAgosto,
            'estadoSeptiembre' => $estadoSeptiembre,
            'estadoOctubre' => $estadoOctubre,
            'estadoNoviembre' => $estadoNoviembre,
            'estadoDiciembre' => $estadoDiciembre,
            'estadoTotal' => $estadoTotal
        );

        if (empty($totalEstimadoCentroCostoVenta)) {
            $totalEstimadoCentroCostoVenta = 0;
        }

        $graficosTotalesEstimadosCentroCostosVentas = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->graficoTotalesEstimadosCentroCostosVentas($planEstimado, $idDivisionCentroCosto);

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Venta - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de ventas por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionVentas/gestionVentaCentroCosto.html.twig', array(
            'idDivisionCentroCosto' => $idDivisionCentroCosto,
            'divisionCentroCosto' => $divisionCentroCosto,
            'centrosCostos' => $centrosCostos,
            'mesesCentrosCostos' => $mesesCentrosCostos,
            'totalEstimadoVenta' => $totalEstimadoVenta,
            'totalEstimadoCentroCostoVenta' => $totalEstimadoCentroCostoVenta,
            'totalEstimadoMesCentroCostoVenta' => $totalEstimadoMesCentroCostoVenta,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobadoEstimadoCentroCostoVenta' => $aprobadoEstimadoCentroCostoVenta,
            'graficosTotalesEstimadosCentroCostosVentas' => $graficosTotalesEstimadosCentroCostosVentas,
            'estadosMeses' => $estadosMeses
        ));

    }

    /**
     * @Route("/insertTotalEstimadoDivisionVenta", name="insertTotalEstimadoDivisionVenta")
     */
    public function insertTotalEstimadoDivisionVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivision')->agregarTotalEstimadoVentaDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto estimado para una división',
            'descripcion' => 'Se asignó el presupuesto estimado de venta a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionVenta", name="updateTotalEstimadoDivisionVenta")
     */
    public function updateTotalEstimadoDivisionVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivision')->modificarTotalEstimadoVentaDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto estimado para una división',
            'descripcion' => 'Se modificó el presupuesto estimado de venta a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoVentaDivisionMes", name="insertTotalEstimadoVentaDivisionMes")
     */
    public
    function insertTotalEstimadoVentaDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->masterAgregarEstimadoVentaDivisionMes($data, $user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivision')->verificarAprobadoEstimadoVentaDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesVenta($data);
            /*$session = new Session();
            $session->set('aprobarEstimadoVentaMes', true);*/

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoVenta", name="insertTotalEstimadoCentroCostoVenta")
     */
    public
    function insertTotalEstimadoCentroCostoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->masterAgregarEstimadoVentaCentroCostoMes($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoVenta", name="updateTotalEstimadoCentroCostoVenta")
     */
    public
    function updateTotalEstimadoCentroCostoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->masterModificarEstimadoVentaCentroCostoMes($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoVenta", name="aprobarPresupuestoEstimadoCentroCostoVenta")
     */
    public
    function aprobarPresupuestoEstimadoCentroCostoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->masterAprobarEstimadoVentaCentroCostoMes($data, $user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->verificarAprobadoEstimadoVentaCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesVenta($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoVentaCentroCostoMes", name="insertTotalEstimadoVentaCentroCostoMes")
     */
    public
    function insertTotalEstimadoVentaCentroCostoMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idCentroCosto' => $peticion->request->get('idCentroCosto'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->masterAgregarEstimadoVentaCentroCostoMes($data, $user);

        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCosto')->verificarAprobadoEstimadoVentaCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoDivision')->aprobarTotalEstimadoCentroCostoMesVenta($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoVentaEstimadoDivisionMensual", name="graficoVentaEstimadoDivisionMensual")
     */
    public
    function graficoVentaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosVentaEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->graficosVentaEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosVentaEstimadoDivisionMensual)) {
            return new Response($graficosVentaEstimadoDivisionMensual);
        }

        $result = json_encode($graficosVentaEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardVentaEstimadoDivisionMensual", name="dashboardVentaEstimadoDivisionMensual")
     */
    public
    function dashboardVentaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosVentaEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->graficosVentaEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosVentas = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->graficoTotalesEstimadosCentroCostosVentas($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosVentaEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosVentas,
        );

        if (is_string($graficosVentaEstimadoDivisionMensual)) {
            return new Response($graficosVentaEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardVentaEstimadoCentroCostoMensual", name="dashboardVentaEstimadoCentroCostoMensual")
     */
    public
    function dashboardVentaEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosVentas = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->graficoTotalesEstimadosMensualCentroCostosVentas($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosVentas)) {
            return new Response($graficosTotalesEstimadosCentroCostosVentas);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosVentas);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoVentaEstimadoCentroCostoMensual", name="graficoVentaEstimadoCentroCostoMensual")
     */
    public
    function graficoVentaEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosVentaEstimadoCentroCostoMensual = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->graficosVentaEstimadoCentroCostoMensual($idPlanEstimado, $centroCosto);

        if (is_string($graficosVentaEstimadoCentroCostoMensual)) {
            return new Response($graficosVentaEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosVentaEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

}
