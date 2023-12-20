<?php

namespace AppBundle\Controller;

use AppBundle\ExportacionExcel\ExportadorExcel;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Filesystem\Filesystem;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class ExportController extends Controller
{

    # -------------------------------------------------------- #
    # ---------- EXPORTACIONES DEL NOMENCLADOR---------------- #
    # -------------------------------------------------------- #

    /**
     * @Route("/exportarListadoNomencladores/{nomenclador}", name="exportarListadoNomencladores")
     * @param $nomenclador
     * @throws Exception
     */
    public function exportarListadoNomencladoresAction($nomenclador)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $actual = new DateTime('now');
        $date = $actual->format('Y-m-d');

        if ($nomenclador === 'CentroCosto') {
            $datos = $em->getRepository('AppBundle:CentroCosto')->datosExportCentrosCostos();
            $titulo = "NOMENCLADOR CENTROS DE COSTOS";
            $subtitulo = "Nomenclador de Centros de Costos";
        } elseif ($nomenclador === 'DenominadorCargo') {
            $datos = $em->getRepository('AppBundle:DenominadorCargo')->datosExportDenominadorCargos();
            $titulo = "NOMENCLADOR DENOMINADOR DE CARGOS";
            $subtitulo = "Nomenclador denominador de cargos";
        } elseif ($nomenclador === 'MateriaPrima') {
            $datos = $em->getRepository('AppBundle:MateriaPrima')->datosExportmateriasPrimas();
            $titulo = "NOMENCLADOR MATERIAS PRIMAS";
            $subtitulo = "Nomenclador materias primas";
        } elseif ($nomenclador === 'TipoCombustible') {
            $datos = $em->getRepository('AppBundle:TipoCombustible')->datosExportTiposCombustibles();
            $titulo = "NOMENCLADOR TIPOS DE COMBUSTIBLES";
            $subtitulo = "Nomenclador tipos de combustibles";
        } elseif ($nomenclador === 'Lubricante') {
            $datos = $em->getRepository('AppBundle:Lubricante')->datosExportLubricante();
            $titulo = "NOMENCLADOR DE ACEITES Y LUBRICANTES";
            $subtitulo = "Nomenclador aceites y lubricantes";
        } elseif ($nomenclador === 'Transporte') {
            $datos = $em->getRepository('AppBundle:Transporte')->datosExportTransportes();
            $titulo = "NOMENCLADOR TRANSPORTE";
            $subtitulo = "Nomenclador transporte";
        } elseif ($nomenclador === 'OtroGasto') {
            $datos = $em->getRepository('AppBundle:OtroGasto')->datosExportOtrosGastos();
            $titulo = "NOMENCLADOR OTROS GASTOS MONETARIOS";
            $subtitulo = "Nomenclador otros gastos";
        }elseif ($nomenclador === 'ModeloTransporte') {
            $datos = $em->getRepository('AppBundle:ModeloTransporte')->datosExportModelosTransportes();
            $titulo = "NOMENCLADOR MODELOS DE TRANSPORTES";
            $subtitulo = "Nomenclador modelos de transportes";
        }elseif ($nomenclador === 'Indicador') {
            $datos = $em->getRepository('AppBundle:Indicador')->datosExportIndicadores();
            $titulo = "NOMENCLADOR INDICADORES";
            $subtitulo = "Nomenclador indicadores";
        }

        $encabezados = array('Fecha' => $date,);
        $nombres = $user->nombreCompleto();

        $excel = new ExportadorExcel();
        $excel->exportarDatosNomencladores($titulo, $subtitulo , $encabezados ,$datos ,$nombres);
    }

    /**
     * @Route("/exportarPlantillaTransporte", name="exportarPlantillaTransporte")
     */
    public function exportarPlantillaTransporteAction()
    {

        $em = $this->getDoctrine()->getManager();
        $divisionesCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $tiposModelo = $em->getRepository('AppBundle:Transporte')->datosExportTiposModelo();
        $transportes = $em->getRepository('AppBundle:Transporte')->datosExportTransportes();

        $excel = new ExportadorExcel();
        $excel->exportarPlantillaTransporte($divisionesCentrosCostos,$tiposModelo,$transportes);
    }

    /**
     * @Route("/exportarPlantillaCargo", name="exportarPlantillaCargo")
     */
    public function exportarPlantillaCargoAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $totalCargos = $em->getRepository('AppBundle:PlantillaCargo')->datosExportPlantillaCargosTotales();
        $cargos = $em->getRepository('AppBundle:PlantillaCargo')->datosExportPlantillaCargos();

        $excel = new ExportadorExcel();
        $excel->exportarPlantillaCargo($totalCargos,$year,$divisionesCentrosCostos,$cargos);
    }

    /**
     * @Route("/exportarCentrosCostos", name="exportarCentrosCostos")
     */
    public function exportarCentrosCostosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $actual = new DateTime('now');
        $date = $actual->format('Y-m-d');
        $centrosCostos = $em->getRepository('AppBundle:CentroCosto')->findAll();

        $titulo = "NOMENCLADOR CENTROS DE COSTOS";
        $subtitulo = "-";
        $encabezados = array(

            'Fecha' => $date,
            'Usuario'   => $user->getNombre(),
        );
        $nombres = $user->getNombre().' '.$user->getPrimerApellido().' '.$user->getSegundoApellido();

        $excel = new ExportadorExcel();
        $excel->exportarDatosNomneclador($titulo, $subtitulo , $encabezados ,$centrosCostos ,$nombres);
    }

    # -------------------------------------------------------- #
    # ------- EXPORTACIONES DEL PLAN DE INDICADORES----------- #
    # -------------------------------------------------------- #

    /**
     * @Route("/exportarPlanEstimadoDivisionCombustible", name="exportarPlanEstimadoDivisionCombustible")
     */
    public function exportarPlanEstimadoDivisionCombustibleAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $totalTransportes = $em->getRepository('AppBundle:PlanEstimadoDivisionCombustible')->datosExportPlanEstimadoDivisionCombustible($planEstimado);
        $totalMesTransportes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesCombustible')->datosExportPlanEstimadoDivisionMesCombustible($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoCombustibleDivision($totalTransportes,$year,$totalMesTransportes,$divisionesCentrosCostos);
    }

    /**
     * @Route("/exportarPlanEstimadoDivisionVenta", name="exportarPlanEstimadoDivisionVenta")
     */
    public function exportarPlanEstimadoDivisionVentaAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionUnica($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->datosExportEstimadoVentaCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoVentaDivision($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoCentroCostoVenta", name="exportarPlanEstimadoCentroCostoVenta")
     */
    public function exportarPlanEstimadoCentroCostoVentaAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionCentroCosto = $user->getCentroCosto()->getDivisionCentroCosto()->getNombre();
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->datosExportEstimadoVentaCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoVentaCentroCosto($year, $divisionCentroCosto, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoFondoSalario", name="exportarPlanEstimadoFondoSalario")
     */
    public function exportarPlanEstimadoFondoSalarioAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->datosExportEstimadoFondoDivisionUnica($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->datosExportEstimadoFondoDivisionMeses($planEstimado);
        $presupuestoDivisionesMesVenta = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->datosExportTotalEstimadoDivisionMesVenta($planEstimado);
        $presupuestoCentrosCostosMesVenta = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->datosExportTotalEstimadoCentroCostoMesVenta($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->datosExportEstimadoFondoCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoFondoSalario($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoDivisionesMesVenta, $presupuestoCentroCostoMes, $presupuestoCentrosCostosMesVenta);
    }

    /**
     * @Route("/exportarPlanEstimadoOtrosGastosOld", name="exportarPlanEstimadoOtrosGastosOld")
     */
    public function exportarPlanEstimadoOtrosGastosOldAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $otrosGastos = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->datosExportEstimadoOtrosGastosUnicos($planEstimado);
        $otrosGastosMes = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->datosExportEstimadoOtrosGastosMes($planEstimado);
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionUnica($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->datosExportEstimadoVentaCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoOtroGasto($year, $otrosGastos, $otrosGastosMes, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoOtrosGastos", name="exportarPlanEstimadoOtrosGastos")
     */
    public function exportarPlanEstimadoOtrosGastosAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $tiposServicios = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->datosExportTipoServicioUnico($planEstimado);
        $tiposServiciosMensual = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->datosExportTipoServicioMensual($planEstimado);
        $otrosGastos = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->datosExportEstimadoOtrosGastosUnicos($planEstimado);
        $otrosGastosMes = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->datosExportEstimadoOtrosGastosMes($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoOtroGasto($year, $tiposServicios, $tiposServiciosMensual, $otrosGastos, $otrosGastosMes);
    }

    /**
     * @Route("/exportarPlanEstimadoMateriaPrima", name="exportarPlanEstimadoMateriaPrima")
     */
    public function exportarPlanEstimadoMateriaPrimaAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivision')->planVentaExportDivisionUnica($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->datosExportEstimadoMateriaPrimaDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesMateriaPrima')->datosExportEstimadoMateriaPrimaCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoMateriaPrima($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoDepreciacion", name="exportarPlanEstimadoDepreciacion")
     */
    public function exportarPlanEstimadoDepreciacionAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionDepreciacion')->planDepreciacionDivisionUnicaExportar($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->datosExportEstimadoDepreciacionDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->datosExportEstimadoDepreciacionCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoDepreciacion($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoBonificacion", name="exportarPlanEstimadoBonificacion")
     */
    public function exportarPlanEstimadoBonificacionAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->planBonificacionDivisionUnicaExportar($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesBonificacion')->datosExportEstimadoBonificacionDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->datosExportEstimadoBonificacionCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoDepreciacion($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoComedor", name="exportarPlanEstimadoComedor")
     */
    public function exportarPlanEstimadoComedorAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionComedor')->planComedorDivisionUnicaExportar($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesComedor')->datosExportEstimadoComedorDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesComedor')->datosExportEstimadoComedorCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoComedor($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoOtrosIngresos", name="exportarPlanEstimadoOtrosIngresos")
     */
    public function exportarPlanEstimadoOtrosIngresosAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->planOtroIngresoDivisionUnicaExport($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtroIngreso')->datosExportEstimadoOtroIngresoDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtroIngreso')->datosExportEstimadoOtroIngresoCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoOtroIngreso($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoAmortizacion", name="exportarPlanEstimadoAmortizacion")
     */
    public function exportarPlanEstimadoAmortizacionAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->planAmortizacionDivisionUnicaExportar($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->datosExportEstimadoAmortizacionDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->datosExportEstimadoAmortizacionCentroCostoMeses($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoAmortizacion($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);
    }

    /**
     * @Route("/exportarPlanEstimadoCombustible", name="exportarPlanEstimadoCombustible")
     */
    public function exportarPlanEstimadoCombustibleAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $tiposCombustibles = $em->getRepository('AppBundle:PlanEstimadoCombustible')->estimadoTipoCombustible($planEstimado);
        $totalMesCombustible = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->presupuestoTipoCombustibleMes($planEstimado);
        $totalMesLubricante = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->presupuestoTipoLubricanteMedioMes($planEstimado);

        $divisionesCentrosCostos = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->planCombustibleDivisionUnicaExportar($planEstimado);
        $presupuestoDivisionesMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionMeses($planEstimado);
        $presupuestoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleCentroCostoMeses($planEstimado);

        $presupuestoDivisionesTipoCombustibles = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionTipoCombustible($planEstimado);
        $presupuestoDivisionesTipoCombustiblesMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionTipoCombustibleMeses($planEstimado);
        $presupuestoDivisionesLubricantes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionLubricantes($planEstimado);
        $presupuestoDivisionesLubricantesMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionLubricantesMeses($planEstimado);

        $presupuestoMedioTransporteCentro = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleMedioCentro($planEstimado);
        $presupuestoMedioTransporteCentroMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleMedioCentroMes($planEstimado);
        $presupuestoMedioTransporteCentroLubricante = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoLubricanteMedioCentro($planEstimado);
        $presupuestoMedioTransporteCentroLubricanteMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoLubricanteMedioCentroMes($planEstimado);
        $presupuestoMedioTransporte = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleMedio($planEstimado);
        $presupuestoMedioTransporteLubricante = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoMedioLubricante($planEstimado);
        $presupuestoMedioTransporteMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleMedioMes($planEstimado);
        $presupuestoMedioTransporteLubricanteMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoLubricanteMedioMes($planEstimado);
        $presupuestoProvinciaAgrupado = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleProvinciaAgrupado($planEstimado);
        $presupuestoProvinciaMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoTipoCombustibleProvinciaMes($planEstimado);
        $presupuestoProvinciaAgrupadoAceite = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoProvinciaAgrupadoAceite($planEstimado);
        $presupuestoProvinciaMesAceite = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportPresupuestoProvinciaMesAceite($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoCombustibleTotal($year,$totalMesCombustible,$totalMesLubricante,$tiposCombustibles, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes, $presupuestoDivisionesTipoCombustiblesMes, $presupuestoDivisionesLubricantesMes, $presupuestoProvinciaAgrupado, $presupuestoProvinciaMes,$presupuestoProvinciaAgrupadoAceite, $presupuestoProvinciaMesAceite, $presupuestoDivisionesTipoCombustibles, $presupuestoMedioTransporteCentro, $presupuestoMedioTransporteCentroMes, $presupuestoMedioTransporte, $presupuestoMedioTransporteMes, $presupuestoDivisionesLubricantes, $presupuestoMedioTransporteCentroLubricante, $presupuestoMedioTransporteCentroLubricanteMes, $presupuestoMedioTransporteLubricante, $presupuestoMedioTransporteLubricanteMes);
        /*$excel->exportarPlanEstimadoCombustibleDivisionCentro($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoCentroCostoMes);*/
        /*$excel->exportarPlanEstimadoCombustibleMedioTransporte($year, $divisionesCentrosCostos, $presupuestoDivisionesMes, $presupuestoDivisionesTipoCombustibles, $presupuestoDivisionesTipoCombustiblesMes, $presupuestoMedioTransporteCentro, $presupuestoMedioTransporteCentroMes, $presupuestoMedioTransporte, $presupuestoMedioTransporteMes, $presupuestoDivisionesLubricantes, $presupuestoDivisionesLubricantesMes, $presupuestoMedioTransporteCentroLubricante, $presupuestoMedioTransporteCentroLubricanteMes, $presupuestoMedioTransporteLubricante, $presupuestoMedioTransporteLubricanteMes);*/
        /*$excel->exportarPlanEstimadoTipoCombustibleProvincia($year, $tiposCombustibles, $presupuestoProvinciaAgrupado, $presupuestoProvinciaMes);*/
        /*$excel->exportarPlanEstimadoLubricanteProvincia($year, $presupuestoProvinciaAgrupadoAceite, $presupuestoProvinciaMesAceite);*/
    }

    /**
     * @Route("/exportarPlanEstimadoGastoMaterial", name="exportarPlanEstimadoGastoMaterial")
     */
    public function exportarPlanEstimadoGastoMaterialAction()
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');

        $presupuestoEnergiaMes  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->graficosEnergiaEstimadoDivisionMensualAgrupado($planEstimado);
        $presupuestoMateriaPrimaMes  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesMateriaPrima')->graficoTotalesEstimadosMesesMateriasPrimas($planEstimado);
        $presupuestoCombustibleMes  = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->graficoEstimadoCombustibleMes($planEstimado);

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoGastoMaterial($year,$presupuestoEnergiaMes,$presupuestoMateriaPrimaMes, $presupuestoCombustibleMes);
    }

    /**
     * @Route("/exportarPlanEstimadoDivision/{division}", name="exportarPlanEstimadoDivision")
     */
    public function exportarPlanEstimadoDivisionAction($division)
    {

        $em = $this->getDoctrine()->getManager();
        $actual = new DateTime('now');
        $year = $actual->format('Y');

        $session = new Session();
        $idPlanEstimado = $session->get('idPlanEstimado');

        //Plan de ventas
        $divisionName = $em->getRepository('AppBundle:DivisionCentroCosto')->findOneBy(array('id' => $division));
        $divisionVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->datosExportEstimadoVentaDivision($idPlanEstimado,$division);
        $divisionVentaMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMes')->datosExportEstimadoVentaDivisionMes($idPlanEstimado,$division);
        $centrosCostosVenta = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->datosExportEstimadoVentaCentroCosto($idPlanEstimado,$division);
        $centrosCostosVentaMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->datosExportEstimadoVentaCentroCostoMes($idPlanEstimado,$division);

        //Plan de recursos humanos
        $divisionSalario = $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->datosExportEstimadoSalarioDivision($idPlanEstimado,$division);
        $divisionSalarioMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesSalario')->datosExportEstimadoSalarioDivisionMes($idPlanEstimado,$division);
        $centrosCostosSalario = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->datosExportEstimadoSalarioCentroCosto($idPlanEstimado,$division);
        $centrosCostosSalarioMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesSalario')->datosExportEstimadoSalarioCentroCostoMes($idPlanEstimado,$division);

        //Plan de Materias Primas
        $divisionMateriaPrima = $em->getRepository('AppBundle:PlanEstimadoDivisionMateriaPrima')->datoExportarTotalEstimadoDivisionMateriaPrima($idPlanEstimado,$division);
        $centrosMateriaPrima = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMateriaPrima')->datoExportarTotalesEstimadosCentrosCostosMateriasPrimas($idPlanEstimado,$division);

        //Plan de Combustible y Lubricantes
        $divisionCombustibleMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleDivisionMes($idPlanEstimado,$division);
        $centrosCombustibleMes = $em->getRepository('AppBundle:PlanEstimadoMesCombustible')->datosExportEstimadoCombustibleCentroMes($idPlanEstimado,$division);

        //Plan de Energia
        $divisionEnergiaMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesEnergia')->datosExportEstimadoEnergiaDivisionMes($idPlanEstimado,$division);
        $centrosEnergiaMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->datosExportEstimadoEnergiaCentroCostoMeses($idPlanEstimado,$division);

        //Plan de Depreciacion
        $divisionDepreciacionMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesDepreciacion')->graficosDepreciacionEstimadoDivisionMensual($idPlanEstimado,$division);
        $centrosDepreciacionMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesDepreciacion')->datosExportEstimadosCentroCostosDepreciaciones($idPlanEstimado,$division);

        //Plan de Amortizacion
        $divisionAmortizacionMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesAmortizacion')->graficosAmortizacionEstimadoDivisionMensual($idPlanEstimado,$division);
        $centrosAmortizacionMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesAmortizacion')->datosExportEstimadosCentroCostosAmortizaciones($idPlanEstimado,$division);

        //Plan de Otros Gastos Monetarios
        $divisionOtroGastoMes = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->datoExportarOtroGastoEstimadoDivisionMensualTodos($idPlanEstimado,$division);
        $centrosOtroGastoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->datoExportarOtroGastoEstimadoCentroMensualTodos($idPlanEstimado,$division);

        /*var_dump($centrosOtroGastoMes);
        exit();*/

        $datos = array(
            'division' => $divisionName ->getNombre(),
            'divisionVenta' => $divisionVenta,
            'divisionVentaMes' => $divisionVentaMes,
            'centrosCostosVenta' => $centrosCostosVenta,
            'centrosCostosVentaMes' => $centrosCostosVentaMes,
            'divisionSalario' => $divisionSalario,
            'divisionSalarioMes' => $divisionSalarioMes,
            'centrosCostosSalario' => $centrosCostosSalario,
            'centrosCostosSalarioMes' => $centrosCostosSalarioMes,
            'divisionMateriaPrima' => $divisionMateriaPrima,
            'centrosMateriaPrima' => $centrosMateriaPrima,
            'divisionCombustibleMes' => $divisionCombustibleMes,
            'centrosCombustibleMes' => $centrosCombustibleMes,
            'divisionEnergiaMes' => $divisionEnergiaMes,
            'centrosEnergiaMes' => $centrosEnergiaMes,
            'divisionDepreciacionMes' => $divisionDepreciacionMes,
            'centrosDepreciacionMes' => $centrosDepreciacionMes,
            'divisionAmortizacionMes' => $divisionAmortizacionMes,
            'centrosAmortizacionMes' => $centrosAmortizacionMes,
            'divisionOtroGastoMes' => $divisionOtroGastoMes,
            'centrosOtroGastoMes' => $centrosOtroGastoMes,

        );

        $excel = new ExportadorExcel();
        $excel->exportarPlanEstimadoDivision($year,$datos);

    }

    # -------------------------------------------------------- #
    # ------- EXPORTACIÓN ÁNALISIS DEL PLAN FINANCIERO-------- #
    # -------------------------------------------------------- #

    /**
     * @Route("/exportarAnalisisPlan", name="exportarAnalisisPlan")
     */
    public function exportarAnalisisPlanAction()
    {
        $periodo = "Acumulado TRIMESTRE";
        $rango = "ENERO-MARZO";
        $excel = new ExportadorExcel();
        $excel->exportarPlanFinanciero($periodo, $rango);
    }

    # -------------------------------------------------------- #
    # ---------------- EXPORTACIONES DEL REAL ---------------- #
    # -------------------------------------------------------- #

    /**
     * @Route("/exportarPlanRealDivision/{year}", name="exportarPlanRealDivision")
     */
    public function exportarPlanRealDivisionAction($year)
    {

        $em = $this->getDoctrine()->getManager();

        $planReaDivision = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivision($year);
        $planReaDivisionServiciosEncabezados = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivisionServiciosEncabezados($year);
        $planReaDivisionServicios = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivisionServicios($year);
        $planReaDivisionMes = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivisionMes($year);
        $planReaDivisionMesServicios = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivisionMesServicios($year);
        $meses = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealDivisionMeses($year);

        $excel = new ExportadorExcel();
        $excel->exportarPlanRealDivision($year,$planReaDivision, $planReaDivisionServiciosEncabezados, $planReaDivisionServicios, $planReaDivisionMes, $planReaDivisionMesServicios, $meses);

    }

    /**
     * @Route("/exportarPlanRealCentroCosto/{year}/{id}", name="exportarPlanRealCentroCosto")
     */
    public function exportarPlanRealCentroCostoAction($year,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $planReaCentroCosto = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCosto($id);
        $planReaCentroCostoServiciosEncabezados = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCostoServiciosEncabezados($id);
        $planReaCentroCostoServicios = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCostoServicios($id);
        $planReaCentroCostoMes = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCostoMes($id);
        $planReaCentroCostoMesServicios = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCostoMesServicios($id);
        $meses = $em->getRepository('AppBundle:PlanRealMes')->datosExportRealCentroCostoMeses($id);

        $excel = new ExportadorExcel();
        $excel->exportarPlanRealCentroCosto($year,$planReaCentroCosto, $planReaCentroCostoServiciosEncabezados, $planReaCentroCostoServicios, $planReaCentroCostoMes, $planReaCentroCostoMesServicios, $meses);

    }

    private function mesEspanol($mesIngles)
    {
        $mes = '';
        if ($mesIngles ==='January') {
            $mes='Enero';
        } else if ($mesIngles ==='February') {
            $mes='Febrero';
        } else if ($mesIngles ==='March') {
            $mes='Marzo';
        } else if ($mesIngles ==='April') {
            $mes='Abril';
        } else if ($mesIngles ==='May') {
            $mes='Mayo';
        } else if ($mesIngles ==='June') {
            $mes='Junio';
        } else if ($mesIngles ==='July') {
            $mes='Julio';
        } else if ($mesIngles ==='August') {
            $mes='Agosto';
        } else if ($mesIngles ==='September') {
            $mes='Septiembre';
        } else if ($mesIngles ==='October') {
            $mes='Octubre';
        } else if ($mesIngles ==='November') {
            $mes='Noviembre';
        } else if ($mesIngles ==='December') {
            $mes='Diciembre';
        }

        return $mes;
    }

}
