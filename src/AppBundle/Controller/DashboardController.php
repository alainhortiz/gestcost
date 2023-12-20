<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboardEstimadoPlanVenta", name="dashboardEstimadoPlanVenta")
     */
    public function dashboardEstimadoPlanVentaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Ventas
        $graficosTotalesEstimadosDivisionesVentas = $em->getRepository('AppBundle:PlanEstimadoDivision')->graficoTotalesEstimadosDivisionesVentas($planEstimado);
        $graficosTotalesEstimadosMesesVentas = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->graficoTotalesEstimadosMesesVentas($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoVenta.html.twig', array(
            'graficosTotalesEstimadosDivisionesVentas' => $graficosTotalesEstimadosDivisionesVentas,
            'graficosTotalesEstimadosMesesVentas' => $graficosTotalesEstimadosMesesVentas,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanOtroIngreso", name="dashboardEstimadoPlanOtroIngreso")
     */
    public function dashboardEstimadoPlanOtroIngresoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Otros Ingresos
        $graficosTotalesEstimadosDivisionesOtrosIngresos  = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->graficoTotalesEstimadosDivisionesOtroIngreso($planEstimado);
        $graficosTotalesEstimadosMesesOtrosIngresos  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->graficoTotalesEstimadosMesesOtrosIngresos($planEstimado);


        return $this->render('Dashboards/dashboardEstimadoOtroIngreso.html.twig', array(
            'graficosTotalesEstimadosDivisionesOtrosIngresos' => $graficosTotalesEstimadosDivisionesOtrosIngresos,
            'graficosTotalesEstimadosMesesOtrosIngresos' => $graficosTotalesEstimadosMesesOtrosIngresos,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanSalario", name="dashboardEstimadoPlanSalario")
     */
    public function dashboardEstimadoPlanSalarioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Fondo de Salario
        $graficosTotalesEstimadosDivisionesFondos  = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->graficoTotalesEstimadosDivisionesFondos($planEstimado);
        $graficosTotalesEstimadosMesesFondos  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->graficosFondoEstimadoDivisionMensualTodos($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoSalario.html.twig', array(
            'graficosTotalesEstimadosDivisionesFondos' => $graficosTotalesEstimadosDivisionesFondos,
            'graficosTotalesEstimadosMesesFondos' => $graficosTotalesEstimadosMesesFondos,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanCombustible", name="dashboardEstimadoPlanCombustible")
     */
    public function dashboardEstimadoPlanCombustibleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        //Dashboard de Combustible
        $graficosTotalesEstimadosCombustibles  = $em->getRepository('AppBundle:PlanEstimadoCombustible')->estimadoTipoCombustible($planEstimado);
        $graficosTotalesEstimadosLubricantes  = $em->getRepository('AppBundle:PlanEstimadoLubricante')->estimadoLubricante($planEstimado);
        $graficosTotalesEstimadosMesesCombustibles  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->graficoEstimadoCombustibleMes($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoCombustible.html.twig', array(
            'graficosTotalesEstimadosCombustibles' => $graficosTotalesEstimadosCombustibles,
            'graficosTotalesEstimadosLubricantes' => $graficosTotalesEstimadosLubricantes,
            'graficosTotalesEstimadosMesesCombustibles' => $graficosTotalesEstimadosMesesCombustibles
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanEnergia", name="dashboardEstimadoPlanEnergia")
     */
    public function dashboardEstimadoPlanEnergiaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Energia
        $graficosTotalesEstimadosDivisionesEnergias  = $em->getRepository('AppBundle:PlanEstimadoDivisionEnergia')->graficoTotalesEstimadosDivisionesEnergias($planEstimado);
        $graficosTotalesEstimadosMesesEnergias  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->graficosEnergiaEstimadoDivisionMensualAgrupado($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoEnergia.html.twig', array(
            'graficosTotalesEstimadosDivisionesEnergias' => $graficosTotalesEstimadosDivisionesEnergias,
            'graficosTotalesEstimadosMesesEnergias' => $graficosTotalesEstimadosMesesEnergias,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanOtrosGastos", name="dashboardEstimadoPlanOtrosGastos")
     */
    public function dashboardEstimadoPlanOtrosGastosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $otrosGastos  = $em->getRepository('AppBundle:OtroGasto')->findAll();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Otros Gastos
        $graficosTotalesEstimadosOtrosGastos  = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->graficoTotalesEstimadosOtrosGastos($planEstimado);
        $graficosTotalesEstimadosMesesOtrosGastos  = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->graficosOtroGastoEstimadoMensualAgrupado($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoOtrosGastos.html.twig', array(
            'graficosTotalesEstimadosOtrosGastos' => $graficosTotalesEstimadosOtrosGastos,
            'graficosTotalesEstimadosMesesOtrosGastos' => $graficosTotalesEstimadosMesesOtrosGastos,
            'otrosGastos' => $otrosGastos,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanMateriasPrimas", name="dashboardEstimadoPlanMateriasPrimas")
     */
    public function dashboardEstimadoPlanMateriasPrimasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Materias Primas
        $graficosTotalesEstimadosDivisionesMateriasPrimas  = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->graficoTotalesEstimadosDivisionesMateriasPrimas($planEstimado);
        $graficosTotalesEstimadosMesesmateriasPrimas  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->graficoTotalesEstimadosMesesMateriasPrimas($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoMateriasPrimas.html.twig', array(
            'graficosTotalesEstimadosDivisionesMateriasPrimas' => $graficosTotalesEstimadosDivisionesMateriasPrimas,
            'graficosTotalesEstimadosMesesmateriasPrimas' => $graficosTotalesEstimadosMesesmateriasPrimas,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanDepreciacion", name="dashboardEstimadoPlanDepreciacion")
     */
    public function dashboardEstimadoPlanDepreciacionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Depreciación
        $graficosTotalesEstimadosDivisionesDepreciaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->graficoTotalesEstimadosDivisionesDepreciacion($planEstimado);
        $graficosTotalesEstimadosMesesDepreciaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->graficoTotalesEstimadosMesesDepreciaciones($planEstimado);


        return $this->render('Dashboards/dashboardEstimadoDepreciacion.html.twig', array(
            'graficosTotalesEstimadosDivisionesDepreciaciones' => $graficosTotalesEstimadosDivisionesDepreciaciones,
            'graficosTotalesEstimadosMesesDepreciaciones' => $graficosTotalesEstimadosMesesDepreciaciones,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanBonificacion", name="dashboardEstimadoPlanBonificacion")
     */
    public function dashboardEstimadoPlanBonificacionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Bonificación
        $graficosTotalesEstimadosDivisionesBonificaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->graficoTotalesEstimadosDivisionesBonificacion($planEstimado);
        $graficosTotalesEstimadosMesesBonificaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->graficoTotalesEstimadosMesesBonificaciones($planEstimado);


        return $this->render('Dashboards/dashboardEstimadoBonificacion.html.twig', array(
            'graficosTotalesEstimadosDivisionesBonificaciones' => $graficosTotalesEstimadosDivisionesBonificaciones,
            'graficosTotalesEstimadosMesesBonificaciones' => $graficosTotalesEstimadosMesesBonificaciones,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanComedor", name="dashboardEstimadoPlanComedor")
     */
    public function dashboardEstimadoPlanComedorAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Comedor
        $graficosTotalesEstimadosDivisionesComedores  = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->graficoTotalesEstimadosDivisionesComedor($planEstimado);
        $graficosTotalesEstimadosMesesComedores  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->graficoTotalesEstimadosMesesComedores($planEstimado);


        return $this->render('Dashboards/dashboardEstimadoComedor.html.twig', array(
            'graficosTotalesEstimadosDivisionesComedores' => $graficosTotalesEstimadosDivisionesComedores,
            'graficosTotalesEstimadosMesesComedores' => $graficosTotalesEstimadosMesesComedores,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanAmortizacion", name="dashboardEstimadoPlanAmortizacion")
     */
    public function dashboardEstimadoPlanAmortizacionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        //Dashboard de Amortización
        $graficosTotalesEstimadosDivisionesAmortizaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->graficoTotalesEstimadosDivisionesAmortizacion($planEstimado);
        $graficosTotalesEstimadosMesesAmortizaciones  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->graficoTotalesEstimadosMesesAmortizaciones($planEstimado);


        return $this->render('Dashboards/dashboardEstimadoAmortizacion.html.twig', array(
            'graficosTotalesEstimadosDivisionesAmortizaciones' => $graficosTotalesEstimadosDivisionesAmortizaciones,
            'graficosTotalesEstimadosMesesAmortizaciones' => $graficosTotalesEstimadosMesesAmortizaciones,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/dashboardEstimadoPlanGastoMaterial", name="dashboardEstimadoPlanGastoMaterial")
     */
    public function dashboardEstimadoPlanGastoMaterialAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        //Dashboard Gasto Material
        $graficosTotalesEstimadosMesesEnergias  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->graficosEnergiaEstimadoDivisionMensualAgrupado($planEstimado);
        $graficosTotalesEstimadosMesesmateriasPrimas  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->graficoTotalesEstimadosMesesMateriasPrimas($planEstimado);
        $graficosTotalesEstimadosMesesCombustibles  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->graficoEstimadoCombustibleMes($planEstimado);

        return $this->render('Dashboards/dashboardEstimadoGastoMaterial.html.twig', array(
            'graficosTotalesEstimadosMesesEnergias' => $graficosTotalesEstimadosMesesEnergias,
            'graficosTotalesEstimadosMesesmateriasPrimas' => $graficosTotalesEstimadosMesesmateriasPrimas,
            'graficosTotalesEstimadosMesesCombustibles' => $graficosTotalesEstimadosMesesCombustibles
        ));

    }

}
