<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoCombustibleController extends Controller
{
    /**
     * @Route("/gestionarEstimadoCombustiblesMediosTransportes", name="gestionarEstimadoCombustiblesMediosTransportes")
     */
    public function gestionarEstimadoCombustiblesMediosTransportesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoCombustible = $session->get('totalEstimadoCombustible');
        $aprobadoEstimadoCombustible = $session->get('aprobadoEstimadoCombustible');
        $tiposCombustibles = $em->getRepository('AppBundle:PlanEstimadoCombustible')->findBy(array('planEstimadoIndicadores' => $planEstimado));
        $tiposTransportes = $em->getRepository('AppBundle:Transporte')->estimadoTipoTransporte();
        $transportes  = $em->getRepository('AppBundle:Transporte')->findAll();
        $tiposCombustiblesAprobados  = $em->getRepository('AppBundle:PlanEstimadoCombustible')->verificarAprobadoEstimadoTipoCombustibleMes($planEstimado);
        $graficosTEstimadosCombustiblesMedios  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->presupuestoTipoCombustibleMedio($planEstimado);
        $graficosTEstimadosCombustiblesMediosMes  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->presupuestoTipoCombustibleMedioMes($planEstimado);
        $graficosTEstimadosCombustiblesMediosCantMeses  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->presupuestoTipoCombustibleMedioCantMeses($planEstimado);
        $mesesTransportes = $this->meses();

        return $this->render('GestionPlanEstimado/GestionCombustible/gestionCombustibleMedioTransporte.html.twig', array(
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobadoEstimadoCombustible' => $aprobadoEstimadoCombustible,
            'totalEstimadoCombustible' => $totalEstimadoCombustible,
            'tiposCombustibles' => $tiposCombustibles,
            'tiposTransportes' => $tiposTransportes,
            'transportes' => $transportes,
            'graficosTEstimadosCombustiblesMedios' => $graficosTEstimadosCombustiblesMedios,
            'graficosTEstimadosCombustiblesMediosMes' => $graficosTEstimadosCombustiblesMediosMes,
            'graficosTEstimadosCombustiblesMediosCantMeses' => $graficosTEstimadosCombustiblesMediosCantMeses,
            'tiposCombustiblesAprobados' => $tiposCombustiblesAprobados,
            'mesesTransportes' => $mesesTransportes
        ));
    }

    /**
     * @Route("/gestionarEstimadoLubricantesMediosTransportes", name="gestionarEstimadoLubricantesMediosTransportes")
     */
    public function gestionarEstimadoLubricantesMediosTransportesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoLubricante = $session->get('totalEstimadoLubricante');
        $aprobadoEstimadoLubricante = $session->get('aprobadoEstimadoLubricante');
        $tiposLubricantes = $em->getRepository('AppBundle:PlanEstimadoLubricante')->findBy(array('planEstimadoIndicadores' => $planEstimado));
        $tiposTransportes = $em->getRepository('AppBundle:Transporte')->estimadoLubricanteTipoTransporte();
        $transportes  = $em->getRepository('AppBundle:Transporte')->findBy(array('isLubricante' => true));
        $tiposLubricantesAprobados  = $em->getRepository('AppBundle:PlanEstimadoLubricante')->verificarAprobadoEstimadoTipoLubricanteMes($planEstimado);
        $graficosTEstimadosLubricantesMedios  = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->presupuestoTipoLubricanteMedio($planEstimado);
        $graficosTEstimadosLubricantesMediosMes  = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->presupuestoTipoLubricanteMedioMes($planEstimado);
        $graficosTEstimadosLubricantesMediosCantMeses  = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->presupuestoTipoLubricanteMedioCantMeses($planEstimado);
        $mesesTransportes = $this->meses();

        return $this->render('GestionPlanEstimado/GestionCombustible/gestionLubricanteMedioTransporte.html.twig', array(
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobadoEstimadoLubricante' => $aprobadoEstimadoLubricante,
            'totalEstimadoLubricante' => $totalEstimadoLubricante,
            'tiposLubricantes' => $tiposLubricantes,
            'tiposTransportes' => $tiposTransportes,
            'transportes' => $transportes,
            'graficosTEstimadosLubricantesMedios' => $graficosTEstimadosLubricantesMedios,
            'graficosTEstimadosLubricantesMediosMes' => $graficosTEstimadosLubricantesMediosMes,
            'graficosTEstimadosLubricantesMediosCantMeses' => $graficosTEstimadosLubricantesMediosCantMeses,
            'tiposLubricantesAprobados' => $tiposLubricantesAprobados,
            'mesesTransportes' => $mesesTransportes
        ));
    }

    /**
     * @Route("/insertCombustibleMedioTrasporteMensual", name="insertCombustibleMedioTrasporteMensual")
     */
    public function insertCombustibleMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = new Session();
        $year = $session->get('yearActivo');
        $data = array(
            'tipoCombustible' => $peticion->request->get('tipoCombustible'),
            'transporte' => $peticion->request->get('transporte'),
            'mes' => $peticion->request->get('mes'),
            'ltsCombustible' => $peticion->request->get('ltsCombustible'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );
        $mesesTransportes = $em->getRepository('AppBundle:PrecioCombustible')->precioTipoCombustible($year,$peticion->request->get('tipoCombustible'));
        $resp = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->masterAgregarTotalCombustibleMedioTransporteMes($data,$mesesTransportes,$user);
        if(is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateCombustibleMedioTrasporteMensual", name="updateCombustibleMedioTrasporteMensual")
     */
    public function updateCombustibleMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $data = array(
            'transporte' => $peticion->request->get('transporte'),
            'mes' => $peticion->request->get('mes'),
            'ltsCombustible' => $peticion->request->get('ltsCombustible'),
            'ltsLubricante' => $peticion->request->get('ltsLubricante'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );
        $mesesTransportes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->presupuestoTipoCombustibleMedioMeses($data['idPlanEstimado'], $data['transporte']);
        $resp = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->masterModificarTotalCombustibleMedioTransporteMes($data, $mesesTransportes ,$user);
        if(is_string($resp)) {
            return new Response($resp);
        }
        return new Response('ok');
    }

    /**
     * @Route("/aprobarCombustibleMedioTrasporteMensual", name="aprobarCombustibleMedioTrasporteMensual")
     */
    public function aprobarCombustibleMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'tipoCombustible' => $peticion->request->get('tipoCombustible')
        );
        $resp = $em->getRepository('AppBundle:PlanEstimadoCombustible')->masterAprobarCombustibleMedioTrasporteMensual($data,$user);
        return new Response($resp);

    }

    /**
     * @Route("/insertLubricanteMedioTrasporteMensual", name="insertLubricanteMedioTrasporteMensual")
     */
    public function insertLubricanteMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = new Session();
        $year = $session->get('yearActivo');
        $data = array(
            'tipoLubricante' => $peticion->request->get('tipoLubricante'),
            'transporte' => $peticion->request->get('transporte'),
            'mes' => $peticion->request->get('mes'),
            'ltsLubricante' => $peticion->request->get('ltsLubricante'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );
        $mesesTransportes = $em->getRepository('AppBundle:PrecioLubricante')->precioTipoLubricante($year,$peticion->request->get('tipoLubricante'));
        $resp = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->masterAgregarTotalLubricanteMedioTransporteMes($data,$mesesTransportes,$user);
        if(is_string($resp)) {
            return new Response($resp);
        }
        return new Response('ok');
    }

    /**
     * @Route("/updateLubricanteMedioTrasporteMensual", name="updateLubricanteMedioTrasporteMensual")
     */
    public function updateLubricanteMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'transporte' => $peticion->request->get('transporte'),
            'mes' => $peticion->request->get('mes'),
            'ltsLubricante' => $peticion->request->get('ltsLubricante'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $mesesTransportes = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->presupuestoTipoLubricanteMedioMeses($data['idPlanEstimado'], $data['transporte']);


        $resp = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->masterModificarTotalLubricanteMedioTransporteMes($data, $mesesTransportes ,$user);

        if(is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/aprobarLubricanteMedioTrasporteMensual", name="aprobarLubricanteMedioTrasporteMensual")
     */
    public function aprobarLubricanteMedioTrasporteMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'tipoLubricante' => $peticion->request->get('tipoLubricante')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoLubricante')->masterAprobarLubricanteMedioTrasporteMensual($data,$user);

        return new Response($resp);

    }

    private function meses() {
        return array(
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'
        );
    }

    /**
     * @Route("/dashboardEstimadoCombustibleMedio", name="dashboardEstimadoCombustibleMedio")
     */
    public function dashboardEstimadoCombustibleMedioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $tipoCombustible = $peticion->request->get('tipoCombustible');

        $graficosTipoCombustibleMedio = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->graficosTipoCombustibleMedio($idPlanEstimado, $tipoCombustible);

        if (is_string($graficosTipoCombustibleMedio)) {
            return new Response($graficosTipoCombustibleMedio);
        }

        $result = json_encode($graficosTipoCombustibleMedio);
        return new JsonResponse($result);

    }

    /**
     * @Route("/dashboardEstimadoCombustibleMedioMes", name="dashboardEstimadoCombustibleMedioMes")
     */
    public function dashboardEstimadoCombustibleMedioMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $tipoCombustible = $peticion->request->get('tipoCombustible');
        $matricula = $peticion->request->get('matricula');

        $graficosTipoCombustibleMedioMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->graficosTipoCombustibleMedioMes($idPlanEstimado, $tipoCombustible, $matricula);

        if (is_string($graficosTipoCombustibleMedioMes)) {
            return new Response($graficosTipoCombustibleMedioMes);
        }

        $result = json_encode($graficosTipoCombustibleMedioMes);
        return new JsonResponse($result);

    }

}
