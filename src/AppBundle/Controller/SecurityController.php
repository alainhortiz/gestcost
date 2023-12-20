<?php

namespace AppBundle\Controller;

use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('Inicio/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            ));
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function login_checkAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/inicio", name="inicio")
     * @throws Exception
     */
    public function inicioAction()
    {
        $hoy = new DateTime('now');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userExpira  = $em->getRepository('AppBundle:Usuario')->findOneBy(array('expira' => true, 'id' => $user->getId()));

        $session = new Session();

        if (!empty($userExpira)) {
            $tiempoClave = $em->getRepository('AppBundle:Usuario')->verificarTiempoPassword($user->getId());

            $session->set('comprobacion', false);

            if (!$session->get('comprobacion') && count($tiempoClave) !== 0) {
                $fechaFinal = new DateTime($tiempoClave[0]['fechaFinClave']->format('Y-m-d'));
                $cantDias = date_diff($hoy, $fechaFinal);
                if ($hoy <= $fechaFinal) {
                    if ($cantDias->days >= 0 && $cantDias->days <= 5) {
                        return $this->render('Inicio/claveAlerta.html.twig', array(
                            'cantDias' => $cantDias->days
                        ));
                    }
                } else {
                    return $this->render('Inicio/claveVencida.html.twig');
                }
                $session->set('comprobacion', true);
            }
        }

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findOneBy(array('aprobado' => false));
        $real = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findOneBy(array('planReal' => true));

        if (empty($activo)) {
            $totalEstimadoVenta = 0;
            $totalEstimadoRecursosHumanos = 0;
            $totalEstimadoOtrosGastos = 0;
            $totalEstimadoCombustible = 0;
            $totalEstimadoLubricante = 0;
            $totalEstimadoEnergia = 0;
            $totalEstimadoMateriaPrima = 0;
            $totalEstimadoDepreciacion = 0;
            $totalEstimadoBonificacion = 0;
            $totalEstimadoComedor = 0;
            $totalEstimadoOtroIngreso = 0;
            $totalEstimadoAmortizacion = 0;
            $yearActivo = $hoy->format('Y');
            $idPlanEstimado = 0;
            $aprobarEstimadoVenta = false;
            $aprobarEstimadoRecursosHumanos = false;
            $aprobarEstimadoOtrosGastos = false;
            $aprobarEstimadoCombustible = false;
            $aprobarEstimadoLubricante = false;
            $aprobarEstimadoEnergia = false;
            $aprobarEstimadoMateriaPrima = false;
            $aprobarEstimadoDepreciacion = false;
            $aprobarEstimadoBonificacion = false;
            $aprobarEstimadoComedor = false;
            $aprobarEstimadoOtroIngreso = false;
            $aprobarEstimadoAmortizacion = false;
            $aprobarEstimadoVentaMes = false;
            $aprobadoPlanVentas = false;
            $aprobadoPlanRecursosHumanos = false;
            $aprobadoPlanEnergia = false;
            $totalEstimadoVentaDivision = 0;
            $aprobarEstimadoFondoCentroCosto = false;
            $aprobarEstimadoEnergiaCentroCosto = false;
            $aprobarEstimadoOtrosGastosDivision = false;
            $aprobarEstimadoOtrosGastosCentroCosto = false;
            $aprobarEstimadoMateriaPrimaDivision = false;
            $aprobadoEstimadoMateriaPrima = false;
            $aprobarEstimadoDepreciacionDivision = false;
            $aprobarEstimadoBonificacionDivision = false;
            $aprobarEstimadoComedorDivision = false;
            $aprobarEstimadoOtroIngresoDivision = false;
            $aprobadoEstimadoDepreciacion = false;
            $aprobadoEstimadoBonificacion = false;
            $aprobadoEstimadoComedor = false;
            $aprobadoEstimadoOtroIngreso = false;
            $aprobadoEstimadoAmortizacion = false;
            $aprobarEstimadoAmortizacionDivision = false;
            $aprobadoEstimadoCombustible = false;
            $aprobadoEstimadoLubricante = false;
            $aprobado = false;
        } else {
            $idPlanEstimado = $activo->getId();
            $yearActivo = $activo->getYear();
            $totalEstimadoVenta = $activo->getTotalVenta();
            $totalEstimadoRecursosHumanos = $activo->getTotalFondoSalario();
            $totalEstimadoOtrosGastos = $activo->getTotalOtrosGastos();
            $totalEstimadoCombustible = $activo->getTotalLtsCombustible();
            $totalEstimadoLubricante = $activo->getTotalLtsLubricante();
            $totalEstimadoEnergia = $activo->getTotalEnergiaPresupuesto();
            $totalEstimadoMateriaPrima = $activo->getTotalMateriaPrima();
            $totalEstimadoDepreciacion = $activo->getTotalDepreciacion();
            $totalEstimadoBonificacion = $activo->getTotalBonificacion();
            $totalEstimadoComedor = $activo->getTotalComedor();
            $totalEstimadoOtroIngreso = $activo->getTotalOtroIngreso();
            $totalEstimadoAmortizacion = $activo->getTotalAmortizacion();
            $aprobarEstimadoVenta = $activo->getAprobarPrespuestoTotalVenta();
            $aprobadoPlanVentas = $activo->getAprobarPrespuestoCentroCostoMesVenta();
            $aprobadoPlanRecursosHumanos = $activo->getAprobarPrespuestoCentroCostoMesRecursosHumanos();
            $aprobadoPlanEnergia = $activo->getAprobarPrespuestoCentroCostoMesEnergia();
            $aprobarEstimadoRecursosHumanos = $activo->getAprobarPrespuestoTotalRecursosHumanos();
            $aprobarEstimadoOtrosGastos = $activo->getAprobarPrespuestoTotalOtrosGastos();
            $aprobarEstimadoCombustible = $activo->getAprobarPrespuestoTotalCombustible();
            $aprobarEstimadoLubricante = $activo->getAprobarPrespuestoTotalLubricante();
            $aprobarEstimadoFondoCentroCosto = $activo->getAprobarPrespuestoDivisionMesRecursosHumanos();
            $aprobarEstimadoEnergiaCentroCosto = $activo->getAprobarPrespuestoDivisionMesEnergia();
            $aprobarEstimadoOtrosGastosDivision = $activo->getAprobarPrespuestoMesOtrosGastos();
            $aprobarEstimadoOtrosGastosCentroCosto = $activo->getAprobarPrespuestoDivisionMesOtrosGastos();
            $aprobarEstimadoEnergia = $activo->getAprobarPrespuestoTotalEnergia();
            $aprobarEstimadoMateriaPrima = $activo->getAprobarPrespuestoTotalMateriaPrima();
            $aprobarEstimadoMateriaPrimaDivision = $activo->getAprobarPrespuestoDivisionMateriaPrima();
            $aprobadoEstimadoMateriaPrima = $activo->getAprobarPrespuestoCentroCostoMateriaPrima();
            $aprobarEstimadoDepreciacion = $activo->getAprobarPrespuestoTotalDepreciacion();
            $aprobarEstimadoBonificacion = $activo->getAprobarPrespuestoTotalBonificacion();
            $aprobarEstimadoComedor = $activo->getAprobarPrespuestoTotalComedor();
            $aprobarEstimadoOtroIngreso = $activo->getAprobarPrespuestoTotalOtroIngreso();
            $aprobarEstimadoDepreciacionDivision = $activo->getAprobarPrespuestoDivisionDepreciacion();
            $aprobarEstimadoBonificacionDivision = $activo->getAprobarPrespuestoDivisionBonificacion();
            $aprobarEstimadoComedorDivision = $activo->getAprobarPrespuestoDivisionComedor();
            $aprobarEstimadoOtroIngresoDivision = $activo->getAprobarPrespuestoDivisionOtroIngreso();
            $aprobadoEstimadoDepreciacion = $activo->getAprobarPrespuestoCentroCostoDepreciacion();
            $aprobadoEstimadoBonificacion = $activo->getAprobarPrespuestoCentroCostoBonificacion();
            $aprobadoEstimadoComedor = $activo->getAprobarPrespuestoCentroCostoComedor();
            $aprobadoEstimadoOtroIngreso = $activo->getAprobarPrespuestoCentroCostoOtroIngreso();
            $aprobarEstimadoAmortizacion = $activo->getAprobarPrespuestoTotalAmortizacion();
            $aprobarEstimadoAmortizacionDivision = $activo->getAprobarPrespuestoDivisionAmortizacion();
            $aprobadoEstimadoAmortizacion = $activo->getAprobarPrespuestoCentroCostoAmortizacion();
            $aprobadoEstimadoCombustible = $activo->getAprobadoEstimadoCombustibleMedioTransporte();
            $aprobadoEstimadoLubricante = $activo->getAprobadoEstimadoLubricanteMedioTransporte();
            $aprobado = $activo->getAprobado();
            $inicioCentroCostoVentas = $em->getRepository('AppBundle:PlanEstimadoDivision')->verificarInicioEstimadoVentaCentroCosto($idPlanEstimado, $user->getCentroCosto()->getDivisionCentroCosto()->getId());
            if (empty($inicioCentroCostoVentas)) {
                $aprobarEstimadoVentaMes = false;
                $totalEstimadoVentaDivision = 0;
            } else {
                $aprobarEstimadoVentaMes = $inicioCentroCostoVentas[0]->getAprobadoPlanVentasMensualDivision();
                $totalEstimadoVentaDivision = $inicioCentroCostoVentas[0]->getTotalVentaDivision();
            }
        }

        $inicioPlanReal = false;

        if (!empty($real)) {
            $inicioPlanReal = $activo->getPlanReal();
        }

        //Real
        $session->set('inicioPlanReal', $inicioPlanReal);
        //Plan
        $session->set('idPlanEstimado', $idPlanEstimado);
        $session->set('yearActivo', $yearActivo);
        $session->set('aprobado', $aprobado);
        $session->set('totalEstimadoVenta', $totalEstimadoVenta);
        $session->set('totalEstimadoRecursosHumanos', $totalEstimadoRecursosHumanos);
        $session->set('totalEstimadoOtrosGastos', $totalEstimadoOtrosGastos);
        $session->set('totalEstimadoCombustible', $totalEstimadoCombustible);
        $session->set('totalEstimadoLubricante', $totalEstimadoLubricante);
        $session->set('totalEstimadoEnergia', $totalEstimadoEnergia);
        $session->set('totalEstimadoMateriaPrima', $totalEstimadoMateriaPrima);
        $session->set('totalEstimadoDepreciacion', $totalEstimadoDepreciacion);
        $session->set('totalEstimadoBonificacion', $totalEstimadoBonificacion);
        $session->set('totalEstimadoComedor', $totalEstimadoComedor);
        $session->set('totalEstimadoOtroIngreso', $totalEstimadoOtroIngreso);
        $session->set('totalEstimadoAmortizacion', $totalEstimadoAmortizacion);
        $session->set('aprobarEstimadoRecursosHumanos', $aprobarEstimadoRecursosHumanos);
        if ($aprobarEstimadoRecursosHumanos) {
            if (($aprobarEstimadoVenta) || ($aprobarEstimadoVentaMes)) {
                $session->set('aprobarEstimadoRecursosHumanos', true);
            }
        }
        $session->set('aprobarEstimadoOtrosGastos', $aprobarEstimadoOtrosGastos);
        $session->set('aprobarEstimadoCombustible', $aprobarEstimadoCombustible);
        $session->set('aprobarEstimadoLubricante', $aprobarEstimadoLubricante);
        $session->set('aprobarEstimadoVenta', $aprobarEstimadoVenta);
        $session->set('aprobarEstimadoEnergia', $aprobarEstimadoEnergia);
        $session->set('aprobarEstimadoMateriaPrima', $aprobarEstimadoMateriaPrima);
        $session->set('aprobarEstimadoDepreciacion', $aprobarEstimadoDepreciacion);
        $session->set('aprobarEstimadoBonificacion', $aprobarEstimadoBonificacion);
        $session->set('aprobarEstimadoComedor', $aprobarEstimadoComedor);
        $session->set('aprobarEstimadoOtroIngreso', $aprobarEstimadoOtroIngreso);
        $session->set('aprobarEstimadoAmortizacion', $aprobarEstimadoAmortizacion);
        if ($aprobarEstimadoMateriaPrima) {
            if (($aprobarEstimadoVenta) || ($aprobarEstimadoVentaMes)) {
                $session->set('aprobarEstimadoMateriaPrima', true);
            }
        }
        $session->set('aprobarEstimadoVentaMes', $aprobarEstimadoVentaMes);
        $session->set('aprobadoPlanVentas', $aprobadoPlanVentas);
        $session->set('aprobadoPlanRecursosHumanos', $aprobadoPlanRecursosHumanos);
        $session->set('aprobadoPlanEnergia', $aprobadoPlanEnergia);
        $session->set('totalEstimadoVentaDivision', $totalEstimadoVentaDivision);
        $session->set('aprobarEstimadoFondoCentroCosto', $aprobarEstimadoFondoCentroCosto);
        $session->set('aprobarEstimadoEnergiaCentroCosto', $aprobarEstimadoEnergiaCentroCosto);
        $session->set('aprobarEstimadoOtrosGastosDivision', $aprobarEstimadoOtrosGastosDivision);
        $session->set('aprobarEstimadoOtrosGastosCentroCosto', $aprobarEstimadoOtrosGastosCentroCosto);
        $session->set('aprobarEstimadoMateriaPrimaDivision', $aprobarEstimadoMateriaPrimaDivision);
        $session->set('aprobadoMateriaPrima', $aprobadoEstimadoMateriaPrima);
        $session->set('aprobarEstimadoDepreciacionDivision', $aprobarEstimadoDepreciacionDivision);
        $session->set('aprobarEstimadoBonificacionDivision', $aprobarEstimadoBonificacionDivision);
        $session->set('aprobarEstimadoComedorDivision', $aprobarEstimadoComedorDivision);
        $session->set('aprobarEstimadoOtroIngresoDivision', $aprobarEstimadoOtroIngresoDivision);
        $session->set('aprobadoDepreciacion', $aprobadoEstimadoDepreciacion);
        $session->set('aprobadoBonificacion', $aprobadoEstimadoBonificacion);
        $session->set('aprobadoComedor', $aprobadoEstimadoComedor);
        $session->set('aprobadoOtroIngreso', $aprobadoEstimadoOtroIngreso);
        $session->set('aprobarEstimadoAmortizacionDivision', $aprobarEstimadoAmortizacionDivision);
        $session->set('aprobadoAmortizacion', $aprobadoEstimadoAmortizacion);
        $session->set('aprobadoCombustible', $aprobadoEstimadoCombustible);
        $session->set('aprobadoLubricante', $aprobadoEstimadoLubricante);

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        return $this->render('Inicio/inicio.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/inicio2", name="inicio2")
     */
    public function inicio2Action()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $hoy = new DateTime('now');

        $session = new Session();

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findOneBy(array('aprobado' => false));
        $real = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findOneBy(array('planReal' => true));

        if (empty($activo)) {
            $totalEstimadoVenta = 0;
            $totalEstimadoRecursosHumanos = 0;
            $totalEstimadoOtrosGastos = 0;
            $totalEstimadoCombustible = 0;
            $totalEstimadoLubricante = 0;
            $totalEstimadoEnergia = 0;
            $totalEstimadoMateriaPrima = 0;
            $totalEstimadoDepreciacion = 0;
            $totalEstimadoBonificacion = 0;
            $totalEstimadoComedor = 0;
            $totalEstimadoOtroIngreso = 0;
            $totalEstimadoAmortizacion = 0;
            $yearActivo = $hoy->format('Y');
            $idPlanEstimado = 0;
            $aprobarEstimadoVenta = false;
            $aprobarEstimadoRecursosHumanos = false;
            $aprobarEstimadoOtrosGastos = false;
            $aprobarEstimadoCombustible = false;
            $aprobarEstimadoLubricante = false;
            $aprobarEstimadoEnergia = false;
            $aprobarEstimadoMateriaPrima = false;
            $aprobarEstimadoDepreciacion = false;
            $aprobarEstimadoBonificacion = false;
            $aprobarEstimadoComedor = false;
            $aprobarEstimadoOtroIngreso = false;
            $aprobarEstimadoAmortizacion = false;
            $aprobarEstimadoVentaMes = false;
            $aprobadoPlanVentas = false;
            $aprobadoPlanRecursosHumanos = false;
            $aprobadoPlanEnergia = false;
            $totalEstimadoVentaDivision = 0;
            $aprobarEstimadoFondoCentroCosto = false;
            $aprobarEstimadoEnergiaCentroCosto = false;
            $aprobarEstimadoOtrosGastosDivision = false;
            $aprobarEstimadoOtrosGastosCentroCosto = false;
            $aprobarEstimadoMateriaPrimaDivision = false;
            $aprobadoEstimadoMateriaPrima = false;
            $aprobarEstimadoDepreciacionDivision = false;
            $aprobarEstimadoBonificacionDivision = false;
            $aprobarEstimadoComedorDivision = false;
            $aprobarEstimadoOtroIngresoDivision = false;
            $aprobadoEstimadoDepreciacion = false;
            $aprobadoEstimadoBonificacion = false;
            $aprobadoEstimadoComedor = false;
            $aprobadoEstimadoOtroIngreso = false;
            $aprobadoEstimadoAmortizacion = false;
            $aprobarEstimadoAmortizacionDivision = false;
            $aprobadoEstimadoCombustible = false;
            $aprobadoEstimadoLubricante = false;
            $aprobado = false;
        } else {
            $idPlanEstimado = $activo->getId();
            $yearActivo = $activo->getYear();
            $totalEstimadoVenta = $activo->getTotalVenta();
            $totalEstimadoRecursosHumanos = $activo->getTotalFondoSalario();
            $totalEstimadoOtrosGastos = $activo->getTotalOtrosGastos();
            $totalEstimadoCombustible = $activo->getTotalLtsCombustible();
            $totalEstimadoLubricante = $activo->getTotalLtsLubricante();
            $totalEstimadoEnergia = $activo->getTotalEnergiaPresupuesto();
            $totalEstimadoMateriaPrima = $activo->getTotalMateriaPrima();
            $totalEstimadoDepreciacion = $activo->getTotalDepreciacion();
            $totalEstimadoBonificacion = $activo->getTotalBonificacion();
            $totalEstimadoComedor = $activo->getTotalComedor();
            $totalEstimadoOtroIngreso = $activo->getTotalOtroIngreso();
            $totalEstimadoAmortizacion = $activo->getTotalAmortizacion();
            $aprobarEstimadoVenta = $activo->getAprobarPrespuestoTotalVenta();
            $aprobadoPlanVentas = $activo->getAprobarPrespuestoCentroCostoMesVenta();
            $aprobadoPlanRecursosHumanos = $activo->getAprobarPrespuestoCentroCostoMesRecursosHumanos();
            $aprobadoPlanEnergia = $activo->getAprobarPrespuestoCentroCostoMesEnergia();
            $aprobarEstimadoRecursosHumanos = $activo->getAprobarPrespuestoTotalRecursosHumanos();
            $aprobarEstimadoOtrosGastos = $activo->getAprobarPrespuestoTotalOtrosGastos();
            $aprobarEstimadoCombustible = $activo->getAprobarPrespuestoTotalCombustible();
            $aprobarEstimadoLubricante = $activo->getAprobarPrespuestoTotalLubricante();
            $aprobarEstimadoFondoCentroCosto = $activo->getAprobarPrespuestoDivisionMesRecursosHumanos();
            $aprobarEstimadoEnergiaCentroCosto = $activo->getAprobarPrespuestoDivisionMesEnergia();
            $aprobarEstimadoOtrosGastosDivision = $activo->getAprobarPrespuestoMesOtrosGastos();
            $aprobarEstimadoOtrosGastosCentroCosto = $activo->getAprobarPrespuestoDivisionMesOtrosGastos();
            $aprobarEstimadoEnergia = $activo->getAprobarPrespuestoTotalEnergia();
            $aprobarEstimadoMateriaPrima = $activo->getAprobarPrespuestoTotalMateriaPrima();
            $aprobarEstimadoMateriaPrimaDivision = $activo->getAprobarPrespuestoDivisionMateriaPrima();
            $aprobadoEstimadoMateriaPrima = $activo->getAprobarPrespuestoCentroCostoMateriaPrima();
            $aprobarEstimadoDepreciacion = $activo->getAprobarPrespuestoTotalDepreciacion();
            $aprobarEstimadoBonificacion = $activo->getAprobarPrespuestoTotalBonificacion();
            $aprobarEstimadoComedor = $activo->getAprobarPrespuestoTotalComedor();
            $aprobarEstimadoOtroIngreso = $activo->getAprobarPrespuestoTotalOtroIngreso();
            $aprobarEstimadoDepreciacionDivision = $activo->getAprobarPrespuestoDivisionDepreciacion();
            $aprobarEstimadoBonificacionDivision = $activo->getAprobarPrespuestoDivisionBonificacion();
            $aprobarEstimadoComedorDivision = $activo->getAprobarPrespuestoDivisionComedor();
            $aprobarEstimadoOtroIngresoDivision = $activo->getAprobarPrespuestoDivisionOtroIngreso();
            $aprobadoEstimadoDepreciacion = $activo->getAprobarPrespuestoCentroCostoDepreciacion();
            $aprobadoEstimadoBonificacion = $activo->getAprobarPrespuestoCentroCostoBonificacion();
            $aprobadoEstimadoComedor = $activo->getAprobarPrespuestoCentroCostoComedor();
            $aprobadoEstimadoOtroIngreso = $activo->getAprobarPrespuestoCentroCostoOtroIngreso();
            $aprobarEstimadoAmortizacion = $activo->getAprobarPrespuestoTotalAmortizacion();
            $aprobarEstimadoAmortizacionDivision = $activo->getAprobarPrespuestoDivisionAmortizacion();
            $aprobadoEstimadoAmortizacion = $activo->getAprobarPrespuestoCentroCostoAmortizacion();
            $aprobadoEstimadoCombustible = $activo->getAprobadoEstimadoCombustibleMedioTransporte();
            $aprobadoEstimadoLubricante = $activo->getAprobadoEstimadoLubricanteMedioTransporte();
            $aprobado = $activo->getAprobado();
            $inicioCentroCostoVentas = $em->getRepository('AppBundle:PlanEstimadoDivision')->verificarInicioEstimadoVentaCentroCosto($idPlanEstimado, $user->getCentroCosto()->getDivisionCentroCosto()->getId());
            if (empty($inicioCentroCostoVentas)) {
                $aprobarEstimadoVentaMes = false;
                $totalEstimadoVentaDivision = 0;
            } else {
                $aprobarEstimadoVentaMes = $inicioCentroCostoVentas[0]->getAprobadoPlanVentasMensualDivision();
                $totalEstimadoVentaDivision = $inicioCentroCostoVentas[0]->getTotalVentaDivision();
            }
        }

        $inicioPlanReal = false;

        if (!empty($real)) {
            $inicioPlanReal = $activo->getPlanReal();
        }

        //Real
        $session->set('inicioPlanReal', $inicioPlanReal);
        //Plan
        $session->set('idPlanEstimado', $idPlanEstimado);
        $session->set('yearActivo', $yearActivo);
        $session->set('aprobado', $aprobado);
        $session->set('totalEstimadoVenta', $totalEstimadoVenta);
        $session->set('totalEstimadoRecursosHumanos', $totalEstimadoRecursosHumanos);
        $session->set('totalEstimadoOtrosGastos', $totalEstimadoOtrosGastos);
        $session->set('totalEstimadoCombustible', $totalEstimadoCombustible);
        $session->set('totalEstimadoLubricante', $totalEstimadoLubricante);
        $session->set('totalEstimadoEnergia', $totalEstimadoEnergia);
        $session->set('totalEstimadoMateriaPrima', $totalEstimadoMateriaPrima);
        $session->set('totalEstimadoDepreciacion', $totalEstimadoDepreciacion);
        $session->set('totalEstimadoBonificacion', $totalEstimadoBonificacion);
        $session->set('totalEstimadoComedor', $totalEstimadoComedor);
        $session->set('totalEstimadoOtroIngreso', $totalEstimadoOtroIngreso);
        $session->set('totalEstimadoAmortizacion', $totalEstimadoAmortizacion);
        $session->set('aprobarEstimadoRecursosHumanos', $aprobarEstimadoRecursosHumanos);
        if ($aprobarEstimadoRecursosHumanos) {
            if (($aprobarEstimadoVenta) || ($aprobarEstimadoVentaMes)) {
                $session->set('aprobarEstimadoRecursosHumanos', true);
            }
        }
        $session->set('aprobarEstimadoOtrosGastos', $aprobarEstimadoOtrosGastos);
        $session->set('aprobarEstimadoCombustible', $aprobarEstimadoCombustible);
        $session->set('aprobarEstimadoLubricante', $aprobarEstimadoLubricante);
        $session->set('aprobarEstimadoVenta', $aprobarEstimadoVenta);
        $session->set('aprobarEstimadoEnergia', $aprobarEstimadoEnergia);
        $session->set('aprobarEstimadoMateriaPrima', $aprobarEstimadoMateriaPrima);
        $session->set('aprobarEstimadoDepreciacion', $aprobarEstimadoDepreciacion);
        $session->set('aprobarEstimadoBonificacion', $aprobarEstimadoBonificacion);
        $session->set('aprobarEstimadoComedor', $aprobarEstimadoComedor);
        $session->set('aprobarEstimadoOtroIngreso', $aprobarEstimadoOtroIngreso);
        $session->set('aprobarEstimadoAmortizacion', $aprobarEstimadoAmortizacion);
        if ($aprobarEstimadoMateriaPrima) {
            if (($aprobarEstimadoVenta) || ($aprobarEstimadoVentaMes)) {
                $session->set('aprobarEstimadoMateriaPrima', true);
            }
        }
        $session->set('aprobarEstimadoVentaMes', $aprobarEstimadoVentaMes);
        $session->set('aprobadoPlanVentas', $aprobadoPlanVentas);
        $session->set('aprobadoPlanRecursosHumanos', $aprobadoPlanRecursosHumanos);
        $session->set('aprobadoPlanEnergia', $aprobadoPlanEnergia);
        $session->set('totalEstimadoVentaDivision', $totalEstimadoVentaDivision);
        $session->set('aprobarEstimadoFondoCentroCosto', $aprobarEstimadoFondoCentroCosto);
        $session->set('aprobarEstimadoEnergiaCentroCosto', $aprobarEstimadoEnergiaCentroCosto);
        $session->set('aprobarEstimadoOtrosGastosDivision', $aprobarEstimadoOtrosGastosDivision);
        $session->set('aprobarEstimadoOtrosGastosCentroCosto', $aprobarEstimadoOtrosGastosCentroCosto);
        $session->set('aprobarEstimadoMateriaPrimaDivision', $aprobarEstimadoMateriaPrimaDivision);
        $session->set('aprobadoMateriaPrima', $aprobadoEstimadoMateriaPrima);
        $session->set('aprobarEstimadoDepreciacionDivision', $aprobarEstimadoDepreciacionDivision);
        $session->set('aprobarEstimadoBonificacionDivision', $aprobarEstimadoBonificacionDivision);
        $session->set('aprobarEstimadoComedorDivision', $aprobarEstimadoComedorDivision);
        $session->set('aprobarEstimadoOtroIngresoDivision', $aprobarEstimadoOtroIngresoDivision);
        $session->set('aprobadoDepreciacion', $aprobadoEstimadoDepreciacion);
        $session->set('aprobadoBonificacion', $aprobadoEstimadoBonificacion);
        $session->set('aprobadoComedor', $aprobadoEstimadoComedor);
        $session->set('aprobadoOtroIngreso', $aprobadoEstimadoOtroIngreso);
        $session->set('aprobarEstimadoAmortizacionDivision', $aprobarEstimadoAmortizacionDivision);
        $session->set('aprobadoAmortizacion', $aprobadoEstimadoAmortizacion);
        $session->set('aprobadoCombustible', $aprobadoEstimadoCombustible);
        $session->set('aprobadoLubricante', $aprobadoEstimadoLubricante);

        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();

        return $this->render('Inicio/inicio.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos
        ));

    }

    /**
     * @Route("/passwordForm", name="passwordForm")
     */
    public function passwordFormAction()
    {
        return $this->render('Nomencladores/GestionUsuario/cambiarPassword.html.twig');
    }

    /**
     * @Route("/changePassword", name="changePassword")
     */
    public function changePasswordAction()
    {
        $peticion = Request::createFromGlobals();
        $idUsuario = $peticion->request->get('idUsuario');
        $username = $peticion->request->get('username');
        $passAnt = $peticion->request->get('passAnt');
        $passNew = $peticion->request->get('passNew');
        $user = $this->getUser();

        $encoder = $this->container->get('security.password_encoder');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Usuario');
        $usuario = $repository->findOneBy(array('username' => $username));
        $valid = $encoder->isPasswordValid($usuario, $passAnt);

        if (!$valid) {
            return new Response('Error: Contraseña actual errónea');
        }

        $resp = $em->getRepository('AppBundle:Usuario')->verificarPassword($passNew);
        if ($resp !== 'ok') {
            return new Response($resp);
        }

        $resp = $em->getRepository('AppBundle:Usuario')->verificarPasswordAnterior($idUsuario, $passNew);
        if ($resp) {
            return new Response('Error: No se puede utilizar la contraseña anterior');
        }

        $resp = $em->getRepository('AppBundle:Usuario')->cambiarPassword($idUsuario, $passNew);
        if (is_string($resp)) {
            return new Response($resp);
        }

        $dataTraza = array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Cambio de contraseña de Usuario',
            'descripcion' => 'Se cambió la contraseña del usuario: ' . $user->getNombre() . ' ' . $user->getPrimerApellido() . ' ' . $user->getSegundoApellido()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);
        if ($traza !== 'ok') {
            return new Response($traza);
        }
        return new Response('ok');
    }

    //metodo para mostrar pantalla de bloqueo

    /**
     * @Route("/lock", name="lock")
     */
    public function lockAction()
    {
        return $this->render('Inicio/lock.html.twig');
    }

    //metodo para desbloquear el sistema

    /**
     * @Route("/confirmPassword", name="confirmPassword")
     */
    public function confirmPasswordAction()
    {
        $peticion = Request::createFromGlobals();
        $password = $peticion->request->get('password');
        $user = $this->getUser();

        $encoder = $this->container->get('security.password_encoder');

        /*$em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Usuario');
        $user = $repository->findOneBy(array('username' => $username));*/
        $valid = $encoder->isPasswordValid($user, $password);

        if ($valid === 1) {
            return new Response('ok');
        }

        return new Response('Error: Contraseña  errónea');
    }
}
