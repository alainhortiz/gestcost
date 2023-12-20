<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoEnergiaController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanEnergiasDivision", name="gestionarEstimadoPlanEnergiasDivision")
     */
    public function gestionarEstimadoPlanEnergiasDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');

        $precio  = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->precioKW($planEstimado);

        if(!$precio) {
            $precio = 0;
        }


        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoEnergia = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->totalEstimadoKWEnergia($planEstimado);
        $totalEstimadoDivisionEnergia = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->totalEstimadoDivisionEnergia($planEstimado);
        $cantidadEstimadoDivisionEnergia = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->cantidadEstimadoDivisionEnergia($planEstimado);
        $cantidadEstimadoDivisionMesEnergia = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->cantidadEstimadoDivisionMesEnergia($planEstimado);

        if(!$totalEstimadoEnergia) {
            $totalEstimadoEnergia = 0;
        }

        if(empty($totalEstimadoDivisionEnergia)) {
            $totalEstimadoDivisionEnergia = 0;
            $porcientoDivisionMensual = 0;
            $aprobarEstimadoEnergiaDivision = false;
        }else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesEnergia / $cantidadEstimadoDivisionEnergia) * 100);
            $aprobarEstimadoEnergiaDivision = $activo[0]->getAprobarPrespuestoDivisionEnergia();
        }

        $graficosTotalesEstimadosDivisionesEnergias  = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->graficoTotalesEstimadosDivisionesEnergias($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Division Energía - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Energía por división'
        ));

        return $this->render('GestionPlanEstimado/GestionEnergia/gestionEnergiaDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'precio' => $precio,
            'totalEstimadoEnergia' => $totalEstimadoEnergia,
            'totalEstimadoDivisionEnergia' => $totalEstimadoDivisionEnergia,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoEnergiaDivision' => $aprobarEstimadoEnergiaDivision,
            'graficosTotalesEstimadosDivisionesEnergias' => $graficosTotalesEstimadosDivisionesEnergias,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanEnergiasCentroCosto", name="gestionarEstimadoPlanEnergiasCentroCosto")
     */
    public function gestionarEstimadoPlanEnergiasCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoEnergia = $session->get('totalEstimadoEnergiaDivision');

        $precio  = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->precioKW($planEstimado);

        if(!$precio) {
            $precio = 0;
        }

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->planEnergiaDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->verificarAprobadoEstimadoEnergiaCentroCostoMes($planEstimado);

        $graficosEnergiaEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->graficosEnergiaEstimadoDivisionMensualTodos($planEstimado);

        $graficosEnergiaEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->graficosEnergiaEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Energía - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Energía por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionEnergia/gestionEnergiaCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'precio' => $precio,
            'totalEstimadoEnergia' => $totalEstimadoEnergia,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosEnergiaEstimadoDivisionMensual' => $graficosEnergiaEstimadoDivisionMensual,
            'graficosEnergiaEstimadoCentroCostoMensual' => $graficosEnergiaEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/insertTotalEstimadoDivisionEnergia", name="insertTotalEstimadoDivisionEnergia")
     */
    public function insertTotalEstimadoDivisionEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'kW' => $peticion->request->get('kW'),
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->masterAgregarEstimadoEnergiaDivision($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/updateTotalEstimadoDivisionEnergia", name="updateTotalEstimadoDivisionEnergia")
     */
    public function updateTotalEstimadoDivisionEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'kW' => $peticion->request->get('kW'),
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->masterModificadorEstimadoEnergiaDivision($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoEnergiaDivisionMes", name="insertTotalEstimadoEnergiaDivisionMes")
     */
    public function insertTotalEstimadoEnergiaDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->masterAgregarEstimadoEnergiaDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->verificarAprobadoEstimadoEnergiaDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesEnergia($data);
            $session = new Session();
            $session->set('aprobarEstimadoEnergiaCentroCosto', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoEnergia", name="insertTotalEstimadoCentroCostoEnergia")
     */
    public function insertTotalEstimadoCentroCostoEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'kW' => $peticion->request->get('kW'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->masterAgregarEstimadoEnergiaCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoEnergia", name="updateTotalEstimadoCentroCostoEnergia")
     */
    public function updateTotalEstimadoCentroCostoEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'kW' => $peticion->request->get('kW'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->masterModificadorEstimadoEnergiaCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoEnergia", name="aprobarPresupuestoEstimadoCentroCostoEnergia")
     */
    public function aprobarPresupuestoEstimadoCentroCostoEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->aprobarTotalEstimadoCentroCostoEnergia($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el total de kW mensual por centros de costos de una división',
            'descripcion' => 'Se aprobó el total de kW mensual por centros de costos para la división: '.$resp->getDivisionCentroCosto()->getNombre()
        ));

        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->verificarAprobadoEstimadoEnergiaCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesEnergia($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoEnergiaCentroCostoMes", name="insertTotalEstimadoEnergiaCentroCostoMes")
     */
    public function insertTotalEstimadoEnergiaCentroCostoMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'kW' => $peticion->request->get('kW'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idCentroCosto' => $peticion->request->get('idCentroCosto'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->masterAgregarEstimadoEnergiaCentroCosto($data,$user);

        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->verificarAprobadoEstimadoEnergiaCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->aprobarTotalEstimadoCentroCostoMesEnergia($data);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoEnergiaEstimadoDivisionMensual", name="graficoEnergiaEstimadoDivisionMensual")
     */
    public function graficoEnergiaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosEnergiaEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->graficosEnergiaEstimadoDivisionMensual($idPlanEstimado,$division);

        if(is_string($graficosEnergiaEstimadoDivisionMensual)) {
            return new Response($graficosEnergiaEstimadoDivisionMensual);
        }

        $result = json_encode($graficosEnergiaEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoEnergiaEstimadoCentroCostoMensual", name="graficoEnergiaEstimadoCentroCostoMensual")
     */
    public function graficoEnergiaEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosEnergiaEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->graficosEnergiaEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosEnergiaEstimadoCentroCostoMensual)) {
            return new Response($graficosEnergiaEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosEnergiaEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardEnergiaEstimadoDivisionMensual", name="dashboardEnergiaEstimadoDivisionMensual")
     */
    public function dashboardEnergiaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosEnergiaEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->datosExportEstimadoEnergiaDivisionMes($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosEnergias = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->datosExportEstimadoEnergiaCentroCostoMes($idPlanEstimado, $division);


        $graficos = array(
            'divisionMensual' => $graficosEnergiaEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosEnergias,
        );

        if (is_string($graficosEnergiaEstimadoDivisionMensual)) {
            return new Response($graficosEnergiaEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardEnergiaEstimadoCentroCostoMensual", name="dashboardEnergiaEstimadoCentroCostoMensual")
     */
    public function dashboardEnergiaEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosEnergias = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->graficosEnergiaEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosEnergias)) {
            return new Response($graficosTotalesEstimadosCentroCostosEnergias);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosEnergias);
        return new JsonResponse($result);

    }
}
