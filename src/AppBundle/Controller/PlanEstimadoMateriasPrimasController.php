<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoMateriasPrimasController extends Controller
{

    /**
     * @Route("/gestionarEstimadoPlanMateriasPrimasDivision", name="gestionarEstimadoPlanMateriasPrimasDivision")
     */
    public function gestionarEstimadoPlanMateriasPrimasDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoMateriaPrima = $session->get('totalEstimadoMateriaPrima');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionMateriaPrima = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->totalEstimadoDivisionMateriaPrima($planEstimado);
        $divisionCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        if (empty($totalEstimadoDivisionMateriaPrima)) {
            $totalEstimadoDivisionMateriaPrima = 0;
            $aprobarEstimadoMateriaPrimaDivision = false;
        } else {
            $aprobarEstimadoMateriaPrimaDivision = $activo[0]->getAprobarPrespuestoDivisionMateriaPrima();
        }

        $graficosTotalesEstimadosDivisionesMateriasPrimas = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->graficoTotalesEstimadosDivisionesMateriasPrimas($planEstimado);


        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Estimado Division MateriaPrima - Gestionar Plan',
            'descripcion' => 'Gestionar el plan estimado de Materias Primas por division'
        ));

        return $this->render('GestionPlanEstimado/GestionPlanMateriasPrimas/gestionMateriaPrimaDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'totalEstimadoMateriaPrima' => $totalEstimadoMateriaPrima,
            'totalEstimadoDivisionMateriaPrima' => $totalEstimadoDivisionMateriaPrima,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoMateriaPrimaDivision' => $aprobarEstimadoMateriaPrimaDivision,
            'graficosTotalesEstimadosDivisionesMateriasPrimas' => $graficosTotalesEstimadosDivisionesMateriasPrimas
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanMateriasPrimasCentroCosto", name="gestionarEstimadoPlanMateriasPrimasCentroCosto")
     */
    public function gestionarEstimadoPlanMateriasPrimasCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoMateriaPrima = $session->get('totalEstimadoMateriaPrima');

        $divisionCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->listarCentroCosto();

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->verificarAprobadoEstimadoMateriaPrimaCentroCosto($planEstimado);

        $graficosMateriaPrimaEstimadoDivision  = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->graficoTotalesEstimadosDivisionesMateriasPrimas($planEstimado);

        $graficosMateriaPrimaEstimadoCentroCosto  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMateriaPrima')->graficoTotalesEstimadosCentrosCostosMateriasPrimas($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Estimado Centro Costo Energía - Gestionar Plan',
            'descripcion' => 'Gestionar el plan estimado de Energía por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionPlanMateriasPrimas/gestionMateriaPrimaCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoMateriaPrima' => $totalEstimadoMateriaPrima,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosMateriaPrimaEstimadoDivision' => $graficosMateriaPrimaEstimadoDivision,
            'graficosMateriaPrimaEstimadoCentroCosto' => $graficosMateriaPrimaEstimadoCentroCosto
        ));

    }

    /**
     * @Route("/insertTotalEstimadoDivisionMateriaPrima", name="insertTotalEstimadoDivisionMateriaPrima")
     */
    public function insertTotalEstimadoDivisionMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->agregarTotalEstimadoMateriaPrimaDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Asignar presupuesto estimado de materias primas para una división',
            'descripcion' => 'Se asignó el presupuesto estimado de materias primas a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoDivisionMateriaPrima", name="updateTotalEstimadoDivisionMateriaPrima")
     */
    public function updateTotalEstimadoDivisionMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->modificarTotalEstimadoMateriaPrimaDivision($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto estimado de materias primaspara una división',
            'descripcion' => 'Se modificó el presupuesto estimado de materias primas a: ' . $resp->getDivisionCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionMateriaPrima", name="aprobarPresupuestoEstimadoDivisionMateriaPrima")
     */
    public function aprobarPresupuestoEstimadoDivisionMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMateriaPrima($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan Estimado de Materias Primas',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan Estimado de Materias Primas del año: '.$resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoMateriaPrimaDivision', true);
        return new Response('ok');
    }

    /**
     * @Route("/graficoMateriaPrimaEstimadoDivisionMensual", name="graficoMateriaPrimaEstimadoDivisionMensual")
     */
    public function graficoMateriaPrimaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosMateriaPrimaEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->graficosMateriaPrimaEstimadoDivisionMensual($idPlanEstimado, $division);

        if (is_string($graficosMateriaPrimaEstimadoDivisionMensual)) {
            return new Response($graficosMateriaPrimaEstimadoDivisionMensual);
        }

        $result = json_encode($graficosMateriaPrimaEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoMateriaPrima", name="insertTotalEstimadoCentroCostoMateriaPrima")
     */
    public function insertTotalEstimadoCentroCostoMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMateriaPrima')->masterAgregarEstimadoMateriaPrimaCentroCosto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoMateriaPrima", name="updateTotalEstimadoCentroCostoMateriaPrima")
     */
    public function updateTotalEstimadoCentroCostoMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMateriaPrima')->masterModificadorEstimadoMateriaPrimaCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoMateriaPrima", name="aprobarPresupuestoEstimadoCentroCostoMateriaPrima")
     */
    public function aprobarPresupuestoEstimadoCentroCostoMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->masterAprobarTotalEstimadoCentroCostoMateriaPrima($data,$user);

        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->verificarAprobadoEstimadoMateriaPrimaCentroCostos($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMateriaPrima($data);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);

    }

    /**
     * @Route("/dashboardMateriaPrimaEstimadoDivisionMensual", name="dashboardMateriaPrimaEstimadoDivisionMensual")
     */
    public function dashboardMateriaPrimaEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosMateriaPrimaEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->graficosMateriaPrimaEstimadoDivisionMensual($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosMateriasPrimas = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesMateriaPrima')->graficoTotalesEstimadosCentroCostosMateriasPrimas($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosMateriaPrimaEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosMateriasPrimas,
        );

        if (is_string($graficosMateriaPrimaEstimadoDivisionMensual)) {
            return new Response($graficosMateriaPrimaEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardMateriaPrimaEstimadoCentroCostoMensual", name="dashboardMateriaPrimaEstimadoCentroCostoMensual")
     */
    public function dashboardMateriaPrimaEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosMateriasPrimas = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesMateriaPrima')->graficoTotalesEstimadosMensualCentroCostosMateriasPrimas($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosMateriasPrimas)) {
            return new Response($graficosTotalesEstimadosCentroCostosMateriasPrimas);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosMateriasPrimas);
        return new JsonResponse($result);

    }

}
