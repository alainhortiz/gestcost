<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoSalarioController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanFondosDivision", name="gestionarEstimadoPlanFondosDivision")
     */
    public function gestionarEstimadoPlanFondosDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));
        $promedioTrabajador  = $em->getRepository('AppBundle:PromedioTrabajador')->findAll();

        if(empty($promedioTrabajador)) {
            $promedioTrabajador = 0;
        }else {
            $promedioTrabajador = $promedioTrabajador[0]->getTotal();
        }

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoFondo = $session->get('totalEstimadoRecursosHumanos');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalEstimadoDivisionFondo = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->totalEstimadoDivisionFondo($planEstimado);
        $cantidadEstimadoDivisionFondo = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->cantidadEstimadoDivisionFondo($planEstimado);
        $cantidadEstimadoDivisionMesFondo = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->cantidadEstimadoDivisionMesFondo($planEstimado);
        $promedioTrabajadorDivision  = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->totalEstimadoDivisionPromedioTrabajador($planEstimado);

        if(empty($totalEstimadoDivisionFondo)) {
            $totalEstimadoDivisionFondo = 0;
            $porcientoDivisionMensual = 0;
            $aprobarEstimadoFondoDivision = false;
        }else {
            $porcientoDivisionMensual = (int)(($cantidadEstimadoDivisionMesFondo / $cantidadEstimadoDivisionFondo) * 100);
            $aprobarEstimadoFondoDivision = $activo[0]->getAprobarPrespuestoDivisionRecursosHumanos();
        }

        $graficosTotalesEstimadosDivisionesFondos  = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->graficoTotalesEstimadosDivisionesFondos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Division Recursos Humanos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Recursos Humanos por división'
        ));

        return $this->render('GestionPlanEstimado/GestionRecursosHumanos/gestionFondoDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'promedioTrabajador' => $promedioTrabajador,
            'promedioTrabajadorDivision' => $promedioTrabajadorDivision,
            'totalEstimadoFondo' => $totalEstimadoFondo,
            'totalEstimadoDivisionFondo' => $totalEstimadoDivisionFondo,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoFondoDivision' => $aprobarEstimadoFondoDivision,
            'graficosTotalesEstimadosDivisionesFondos' => $graficosTotalesEstimadosDivisionesFondos,
            'porcientoDivisionMensual' => $porcientoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanFondosCentroCosto", name="gestionarEstimadoPlanFondosCentroCosto")
     */
    public function gestionarEstimadoPlanFondosCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoFondo = $session->get('totalEstimadoFondoDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->planFondoDivisionUnica($planEstimado);

        $divisionesAprobadas  = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->verificarAprobadoEstimadoFondoCentroCostoMes($planEstimado);

        $graficosFondoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->graficosFondoEstimadoDivisionMensualTodos($planEstimado);

        $graficosFondoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->graficosFondoEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Recursos Humanos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Recursos Humanos por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionRecursosHumanos/gestionFondoCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'totalEstimadoFondo' => $totalEstimadoFondo,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'divisionesAprobadas' => $divisionesAprobadas,
            'graficosFondoEstimadoDivisionMensual' => $graficosFondoEstimadoDivisionMensual,
            'graficosFondoEstimadoCentroCostoMensual' => $graficosFondoEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/insertTotalEstimadoDivisionFondo", name="insertTotalEstimadoDivisionFondo")
     */
    public function insertTotalEstimadoDivisionFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'promedioTrabajador' => $peticion->request->get('promedioTrabajador'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->masterAgregarEstimadoFondoDivision($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/updateTotalEstimadoDivisionFondo", name="updateTotalEstimadoDivisionFondo")
     */
    public function updateTotalEstimadoDivisionFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'promedioTrabajador' => $peticion->request->get('promedioTrabajador'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->masterModificadorEstimadoFondoDivision($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoFondoDivisionMes", name="insertTotalEstimadoFondoDivisionMes")
     */
    public function insertTotalEstimadoFondoDivisionMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'promedioTrabajador' => $peticion->request->get('promedioTrabajador'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->masterAgregarEstimadoFondoDivisionMes($data,$user);

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->verificarAprobadoEstimadoFondoDivisionMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesFondo($data);
            $session = new Session();
            $session->set('aprobarEstimadoFondoCentroCosto', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoFondo", name="insertTotalEstimadoCentroCostoFondo")
     */
    public function insertTotalEstimadoCentroCostoFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes'),
            'trabajadorPromedio' => $peticion->request->get('trabajadorPromedio')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->masterAgregarEstimadoFondoCentroCosto($data,$user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoFondo", name="updateTotalEstimadoCentroCostoFondo")
     */
    public function updateTotalEstimadoCentroCostoFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes'),
            'trabajadorPromedio' => $peticion->request->get('trabajadorPromedio')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->masterModificadorEstimadoFondoCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoFondo", name="aprobarPresupuestoEstimadoCentroCostoFondo")
     */
    public function aprobarPresupuestoEstimadoCentroCostoFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'division' => $peticion->request->get('division')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->aprobarTotalEstimadoCentroCostoFondo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar fondo de salario mensual por centros de costos de una división',
            'descripcion' => 'Se aprobó el fondo de salario mensual por centros de costos para la división: '.$resp->getDivisionCentroCosto()->getNombre()
        ));

        // verificar si todas las divisiones con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->verificarAprobadoEstimadoFondoCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoCentroCostoMesFondo($data);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoFondoCentroCostoMes", name="insertTotalEstimadoFondoCentroCostoMes")
     */
    public function insertTotalEstimadoFondoCentroCostoMesAction()
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

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->masterAgregarEstimadoFondoCentroCostoMes($data,$user);

        // verificar si todas las centros de costos con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoCentroCostoSalario')->verificarAprobadoEstimadoFondoCentroCostoMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->aprobarTotalEstimadoCentroCostoMesFondo($data);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoFondoEstimadoDivisionMensual", name="graficoFondoEstimadoDivisionMensual")
     */
    public function graficoFondoEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosFondoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->graficosFondoEstimadoDivisionMensual($idPlanEstimado,$division);

        if(is_string($graficosFondoEstimadoDivisionMensual)) {
            return new Response($graficosFondoEstimadoDivisionMensual);
        }

        $result = json_encode($graficosFondoEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoFondoEstimadoCentroCostoMensual", name="graficoFondoEstimadoCentroCostoMensual")
     */
    public function graficoFondoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosFondoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->graficosFondoEstimadoCentroCostoMensual($idPlanEstimado,$centroCosto);

        if(is_string($graficosFondoEstimadoCentroCostoMensual)) {
            return new Response($graficosFondoEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosFondoEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardSalarioEstimadoDivisionMensual", name="dashboardSalarioEstimadoDivisionMensual")
     */
    public
    function dashboardSalarioEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');

        $graficosFondoEstimadoDivisionMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->datosExportEstimadoSalarioDivisionMes($idPlanEstimado, $division);
        $graficosTotalesEstimadosCentroCostosFondos = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->datosExportEstimadoSalarioCentroCostoMes($idPlanEstimado, $division);

        $graficos = array(
            'divisionMensual' => $graficosFondoEstimadoDivisionMensual,
            'divisionCentroCosto' => $graficosTotalesEstimadosCentroCostosFondos,
        );

        if (is_string($graficosFondoEstimadoDivisionMensual)) {
            return new Response($graficosFondoEstimadoDivisionMensual);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardFondoEstimadoCentroCostoMensual", name="dashboardFondoEstimadoCentroCostoMensual")
     */
    public
    function dashboardFondoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $centro = $peticion->request->get('centro');

        $graficosTotalesEstimadosCentroCostosFondos = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->graficosFondoEstimadoCentroCostoMensual($idPlanEstimado, $centro);

        if (is_string($graficosTotalesEstimadosCentroCostosFondos)) {
            return new Response($graficosTotalesEstimadosCentroCostosFondos);
        }

        $result = json_encode($graficosTotalesEstimadosCentroCostosFondos);
        return new JsonResponse($result);

    }

}
