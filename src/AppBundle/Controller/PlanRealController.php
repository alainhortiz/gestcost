<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanRealController extends Controller
{
    /**
     * @Route("/gestionarPlanesReales", name="gestionarPlanesReales")
     */
    public function gestionarPlanesRealesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $planReal = null;
        $realMeses = null;
        $planesReales = $em->getRepository('AppBundle:PlanReal')->findAll();
        $planReal = $em->getRepository('AppBundle:PlanReal')->findOneBy(array('isCerrado' => false));

        if ($planReal) {
            $realMeses = $em->getRepository('AppBundle:PlanRealMes')->findBy(array('planReal' => $planReal));
            $serviciosRecibidos = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidos($planReal->getId());
            $serviciosRecibidosMeses = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidosMeses($planReal->getId());
            $serviciosRecibidosDivision = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidosDivision($planReal->getId());
            $serviciosRecibidosDivisionMeses = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidosDivisionMeses($planReal->getId());
            $serviciosRecibidosCentroCosto = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidosCentroCosto($planReal->getId());
            $serviciosRecibidosCentroCostoMeses = $em->getRepository('AppBundle:PlanRealMesServicio')->datosServiciosRecibidosCentroCostoMeses($planReal->getId());
        }

        return $this->render('GestionPlanReal/gestionPlanReal.html.twig', array(
            'planesReales' => $planesReales,
            'planReal' => $planReal,
            'realMeses' => $realMeses,
            'serviciosRecibidos' => $serviciosRecibidos,
            'serviciosRecibidosMeses' => $serviciosRecibidosMeses,
            'serviciosRecibidosDivision' => $serviciosRecibidosDivision,
            'serviciosRecibidosDivisionMeses' => $serviciosRecibidosDivisionMeses,
            'serviciosRecibidosCentroCosto' => $serviciosRecibidosCentroCosto,
            'serviciosRecibidosCentroCostoMeses' => $serviciosRecibidosCentroCostoMeses
        ));
    }

    /**
     * @Route("/localizarPlanReal/{year}", name="localizarPlanReal")
     */
    public function localizarPlanRealAction($year)
    {
        $em = $this->getDoctrine()->getManager();

        $planReal = null;
        $realMeses = null;
        $planesReales = $em->getRepository('AppBundle:PlanReal')->findAll();
        $planReal = $em->getRepository('AppBundle:PlanReal')->findOneBy(array('year' => $year));

        if ($planReal) {
            $realMeses = $em->getRepository('AppBundle:PlanRealMes')->findBy(array('planReal' => $planReal));
        }

        return $this->render('GestionPlanReal/gestionPlanReal.html.twig', array(
            'planesReales' => $planesReales,
            'planReal' => $planReal,
            'realMeses' => $realMeses
        ));
    }

    /**
     * @Route("/addPlanReal/{id}/{mes}", name="addPlanReal")
     */
    public function addPlanRealAction($id,$mes)
    {
        $em = $this->getDoctrine()->getManager();
        $planReal = null;
        $realMeses = null;
        $divisionCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));
        $tiposServicios = $em->getRepository('AppBundle:TipoServicio')->findAll();
        $servicios = $em->getRepository('AppBundle:OtroGasto')->findBy(array('isActive' => true));
        $planReal = $em->getRepository('AppBundle:PlanReal')->find($id);

        if ($planReal) {
            $realMeses = $em->getRepository('AppBundle:PlanRealMes')->findBy(array('planReal' => $planReal));
        }

        return $this->render('GestionPlanReal/addPlanReal.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'tiposServicios' => $tiposServicios,
            'servicios' => $servicios,
            'realMeses' => $realMeses,
            'planReal' => $planReal,
            'mes' => $mes
        ));
    }

    /**
     * @Route("/insertPlanRealMensual", name="insertPlanRealMensual")
     */
    public function insertPlanRealMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'planReal' => $peticion->request->get('planReal'),
            'mes' => $peticion->request->get('mes'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'ventaTotal' => $peticion->request->get('ventaTotal'),
            'otroIngreso' => $peticion->request->get('otroIngreso'),
            'salario' => $peticion->request->get('salario'),
            'vacaciones' => $peticion->request->get('vacaciones'),
            'seguridadSocial' => $peticion->request->get('seguridadSocial'),
            'materiaPrima' => $peticion->request->get('materiaPrima'),
            'combustibleLubricante' => $peticion->request->get('combustibleLubricante'),
            'energia' => $peticion->request->get('energia'),
            'depreciacion' => $peticion->request->get('depreciacion'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'gastoFinanciero' => $peticion->request->get('gastoFinanciero'),
            'impuestoTerrestre' => $peticion->request->get('impuestoTerrestre'),
            'otroGastoMonetario' => $peticion->request->get('otroGastoMonetario'),
            'gastoComedor' => $peticion->request->get('gastoComedor'),
            'gastoxPerdida' => $peticion->request->get('gastoxPerdida'),
            'totalesServicios' => $peticion->request->get('totalesServicios')
        );

        $resp = $em->getRepository('AppBundle:PlanRealMes')->masterAgregarRealMensual($data,$user);

        if(is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/editPlanReal/{idReal}/{mes}/{id}", name="editPlanReal")
     */
    public function editPlanRealAction($idReal,$mes,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $realMes = null;
        $divisionCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));
        $planReal = $em->getRepository('AppBundle:PlanReal')->find($idReal);
        $realMes = $em->getRepository('AppBundle:PlanRealMes')->find($id);
        $realMesServicios = $em->getRepository('AppBundle:PlanRealMesServicio')->findBy(array('planRealMes' => $id));
        $tiposServicios = $em->getRepository('AppBundle:TipoServicio')->findAll();
        $servicios = $em->getRepository('AppBundle:OtroGasto')->findBy(array('isActive' => true));

        return $this->render('GestionPlanReal/editPlanReal.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'realMes' => $realMes,
            'planReal' => $planReal,
            'realMesServicios' => $realMesServicios,
            'tiposServicios' => $tiposServicios,
            'servicios' => $servicios,
            'mes' => $mes
        ));
    }

    /**
     * @Route("/updatePlanRealMensual", name="updatePlanRealMensual")
     */
    public function updatePlanRealMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'mes' => $peticion->request->get('mes'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'ventaTotal' => $peticion->request->get('ventaTotal'),
            'otroIngreso' => $peticion->request->get('otroIngreso'),
            'salario' => $peticion->request->get('salario'),
            'vacaciones' => $peticion->request->get('vacaciones'),
            'seguridadSocial' => $peticion->request->get('seguridadSocial'),
            'materiaPrima' => $peticion->request->get('materiaPrima'),
            'combustibleLubricante' => $peticion->request->get('combustibleLubricante'),
            'energia' => $peticion->request->get('energia'),
            'depreciacion' => $peticion->request->get('depreciacion'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'gastoFinanciero' => $peticion->request->get('gastoFinanciero'),
            'impuestoTerrestre' => $peticion->request->get('impuestoTerrestre'),
            'otroGastoMonetario' => $peticion->request->get('otroGastoMonetario'),
            'gastoComedor' => $peticion->request->get('gastoComedor'),
            'gastoxPerdida' => $peticion->request->get('gastoxPerdida'),
            'totalesServicios' => $peticion->request->get('totalesServicios')
        );

        $resp = $em->getRepository('AppBundle:PlanRealMes')->masterModificarRealMensual($data,$user);

        if(is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deletePlanRealMensual", name="deletePlanRealMensual")
     */
    public function deletePlanRealMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $idReal = $peticion->request->get('idReal');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:PlanRealMes')->eliminarRealMensual($idReal);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar real mensual del Centro de Costo',
            'descripcion' => 'Se eliminó el real mensual del Centro de Costo: '.$resp->getCentroCosto()->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/cerrarPlanRealMensual", name="cerrarPlanRealMensual")
     */
    public function cerrarPlanRealMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'planReal' => $peticion->request->get('planReal'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanReal')->cerrarRealMes($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Cerrar plan real mensual',
            'descripcion' => 'Se cerró el plan real del mes: ' . $peticion->request->get('mes')
        ));
        return new Response('ok');
    }

}
