<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoIndicadoresController extends Controller
{
    /**
     * @Route("/gestionarPlanesEstimados", name="gestionarPlanesEstimados")
     */
    public function gestionarPlanesEstimadosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $planesEstimados = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findBy(array('aprobado' => false));
        $year = null;

        if ($planesEstimados) {
            $year = $planesEstimados[0]->getYear();
        }

        $tiposCombustiblesPrecios = $em->getRepository('AppBundle:TipoCombustible')->tipoCombustiblePrecio($year);
        $tiposLubricantesPrecios = $em->getRepository('AppBundle:Lubricante')->tipoLubricantePrecio($year);

        $graficosTotales = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->graficoTotales();
        $graficosCombustibles = $em->getRepository('AppBundle:PlanEstimadoCombustible')->estimadoTipoCombustible($planEstimado);
        $graficosLubricantes = $em->getRepository('AppBundle:PlanEstimadoLubricante')->estimadoLubricante($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Indicadores - Gestionar Planes Estimados',
            'descripcion' => 'Listado de Planes'
        ));

        return $this->render('GestionPlanEstimado/GestionPlanEstimado/gestionPlanEstimado.html.twig', array(
            'planesEstimados' => $planesEstimados,
            'year' => $year,
            'graficosTotales' => $graficosTotales,
            'graficosCombustibles' => $graficosCombustibles,
            'tiposCombustiblesPrecios' => $tiposCombustiblesPrecios,
            'tiposLubricantesPrecios' => $tiposLubricantesPrecios,
            'graficosLubricantes' => $graficosLubricantes
        ));
    }

    /**
     * @Route("/estadoProcesosDivision", name="estadoProcesosDivision")
     */
    public function estadoProcesosDivisionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $procesos = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($peticion->request->get('idPlanEstimado'));

        if (is_string($procesos)) {
            return new Response($procesos);
        }

        return new Response($this->renderView('Inicio/tablaEstadosProcesosDivision.html.twig', array('procesos' => $procesos)));
    }

    /**
     * @Route("/inicioPlanEstimado", name="inicioPlanEstimado")
     */
    public function inicioPlanEstimadoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $year = $peticion->request->get('year');

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->iniciarPlanEstimado($year);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Iniciar Plan',
            'descripcion' => 'Se inicio el Plan del año: ' . $year
        ));
        $session = new Session();
        $session->set('idPlanEstimado', $resp->getId());
        $session->set('yearActivo', $resp->getYear());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEstimadoVenta", name="insertTotalEstimadoVenta")
     */
    public function insertTotalEstimadoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalEstimadoVenta($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total del Plan de Ventas',
            'descripcion' => 'Se inicio el Plan de Venta del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoVenta', $resp->getTotalVenta());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEstimadoVenta", name="updateTotalEstimadoVenta")
     */
    public function updateTotalEstimadoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalEstimadoVenta($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar presupuesto del Plan de Venta',
            'descripcion' => 'Se modificó el presupuesto del Plan de Venta del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoVenta', $resp->getTotalVenta());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalPresupuestoEstimadoVenta", name="aprobarTotalPresupuestoEstimadoVenta")
     */
    public function aprobarTotalPresupuestoEstimadoVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoVenta($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto del Plan de Venta',
            'descripcion' => 'Se aprobó el presupuesto del Plan de Venta del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoVenta', $resp->getAprobarPrespuestoTotalVenta());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionVenta", name="aprobarPresupuestoEstimadoDivisionVenta")
     */
    public function aprobarPresupuestoEstimadoDivisionVentaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionVenta($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto por divisiones del Plan de Venta',
            'descripcion' => 'Se aprobó el presupuesto por divisiones del Plan de Venta del año: ' . $resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalFondoSalario", name="insertTotalFondoSalario")
     */
    public function insertTotalFondoSalarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalFondoSalario($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de fondo de Salario',
            'descripcion' => 'Se insertó el fondo de salario del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoRecursosHumanos', $resp->getTotalFondoSalario());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalFondoSalario", name="updateTotalFondoSalario")
     */
    public function updateTotalFondoSalarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalFondoSalario($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de fondo de Salario',
            'descripcion' => 'Se modificó el fondo de salario del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoRecursosHumanos', $resp->getTotalFondoSalario());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalFondoSalario", name="aprobarTotalFondoSalario")
     */
    public function aprobarTotalFondoSalarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalFondoSalario($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de Recursos Humanos',
            'descripcion' => 'Se aprobó el presupuesto de recursos humanos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoRecursosHumanos', $resp->getAprobarPrespuestoTotalRecursosHumanos());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionFondo", name="aprobarPresupuestoEstimadoDivisionFondo")
     */
    public function aprobarPresupuestoEstimadoDivisionFondoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionFondo($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar fondo de salario por divisiones del Plan de Recursos Humanos',
            'descripcion' => 'Se aprobó el fondo de salario por divisiones del Plan de Recursos Humanos del año: ' . $resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalOtroGastos", name="insertTotalOtroGastos")
     */
    public function insertTotalOtroGastosAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalOtrosGastos($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Otros Gastos',
            'descripcion' => 'Se insertó el total de otros gastos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoOtrosGastos', $resp->getTotalOtrosGastos());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalOtroGastos", name="updateTotalOtroGastos")
     */
    public function updateTotalOtroGastosAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalOtrosGastos($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Otros Gastos',
            'descripcion' => 'Se modificó el total de otros gastos del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoOtrosGastos', $resp->getTotalOtrosGastos());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalOtroGastos", name="aprobarTotalOtroGastos")
     */
    public function aprobarTotalOtroGastosAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalOtrosGastos($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de otros gastos monetarios',
            'descripcion' => 'Se aprobó el presupuesto de otros gastos monetarios del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoOtrosGastos', $resp->getAprobarPrespuestoTotalOtrosGastos());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalCombustible", name="insertTotalCombustible")
     */
    public function insertTotalCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'tipoCombustible' => $peticion->request->get('tipoCombustible'),
            'lts' => $peticion->request->get('lts'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCombustible')->masterAgregarTotalCombustible($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateTotalCombustible", name="updateTotalCombustible")
     */
    public function updateTotalCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'tipoCombustible' => $peticion->request->get('tipoCombustible'),
            'ltsAnterior' => $peticion->request->get('ltsAnterior'),
            'lts' => $peticion->request->get('lts'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCombustible')->masterModificarTotalCombustible($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalCombustible", name="aprobarTotalCombustible")
     */
    public function aprobarTotalCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalCombustible($idPlanEstimado);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar litros para el combustible',
            'descripcion' => 'Se aprobó la cantidad de litros para el combustible del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoCombustible', $resp->getAprobarPrespuestoTotalCombustible());

        return new Response('ok');
    }

    /**
     * @Route("/insertTotalLubricante", name="insertTotalLubricante")
     */
    public function insertTotalLubricanteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'tipoLubricante' => $peticion->request->get('tipoLubricante'),
            'lts' => $peticion->request->get('lts'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCombustible')->masterAgregarTotalLubricante($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateTotalLubricante", name="updateTotalLubricante")
     */
    public function updateTotalLubricanteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'tipoLubricante' => $peticion->request->get('tipoLubricante'),
            'ltsAnterior' => $peticion->request->get('ltsAnterior'),
            'lts' => $peticion->request->get('lts'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCombustible')->masterModificarTotalLubricante($data, $user);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalLubricante", name="aprobarTotalLubricante")
     */
    public function aprobarTotalLubricanteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalLubricante($idPlanEstimado);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar litros para el aceite',
            'descripcion' => 'Se aprobó la cantidad de litros para el aceite del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoLubricante', $resp->getAprobarPrespuestoTotalLubricante());

        return new Response('ok');

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionCombustible", name="aprobarPresupuestoEstimadoDivisionCombustible")
     */
    public function aprobarPresupuestoEstimadoDivisionCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionCombustible($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el combustible por divisiones',
            'descripcion' => 'Se aprobó el combustible por divisiones del año: ' . $resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalEnergia", name="insertTotalEnergia")
     */
    public function insertTotalEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'kW' => $peticion->request->get('kW')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalEnergia($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total para el consumo de energía',
            'descripcion' => 'Se insertó el total para el consumo de energía del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoEnergia', $resp->getTotalEnergiaPresupuesto());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalEnergia", name="updateTotalEnergia")
     */
    public function updateTotalEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'kW' => $peticion->request->get('kW')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalEnergia($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total para el consumo de energía',
            'descripcion' => 'Se modificó el total para el el consumo de energía del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoEnergia', $resp->getTotalEnergiaPresupuesto());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalEnergia", name="aprobarTotalEnergia")
     */
    public function aprobarTotalEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEnergia($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto para el consumo de energía',
            'descripcion' => 'Se aprobó el presupuesto para el consumo de energía del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoEnergia', $resp->getAprobarPrespuestoTotalEnergia());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionEnergia", name="aprobarPresupuestoEstimadoDivisionEnergia")
     */
    public function aprobarPresupuestoEstimadoDivisionEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionEnergia($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar total de kW por divisiones del Plan de Energía',
            'descripcion' => 'Se aprobó el total de kW por divisiones del Plan de Energía del año: ' . $resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/aprobarPresupuestoEstimadoOtroGasto", name="aprobarPresupuestoEstimadoOtroGasto")
     */
    public function aprobarPresupuestoEstimadoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoOtroGasto($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el presupuesto por servicios del Plan de Otros Gastos',
            'descripcion' => 'Se aprobó el presupuesto por servicios del Plan de Otros Gastos del año: ' . $resp->getYear()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalMateriaPrima", name="insertTotalMateriaPrima")
     */
    public function insertTotalMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalMateriaPrima($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Materias Primas',
            'descripcion' => 'Se insertó el total de materias primas del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoMateriaPrima', $resp->getTotalMateriaPrima());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalMateriaPrima", name="updateTotalMateriaPrima")
     */
    public function updateTotalMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalMateriaPrima($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Materias Primas',
            'descripcion' => 'Se modificó el total de materias primas del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoMateriaPrima', $resp->getTotalMateriaPrima());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalMateriaPrima", name="aprobarTotalMateriaPrima")
     */
    public function aprobarTotalMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalMateriaPrima($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de materias primas',
            'descripcion' => 'Se aprobó el presupuesto de materias primas del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoMateriaPrima', $resp->getAprobarPrespuestoTotalMateriaPrima());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalDepreciacion", name="insertTotalDepreciacion")
     */
    public function insertTotalDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalDepreciacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Depreciación',
            'descripcion' => 'Se insertó el total de Depreciación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoDepreciacion', $resp->getTotalDepreciacion());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalDepreciacion", name="updateTotalDepreciacion")
     */
    public function updateTotalDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalDepreciacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Depreciación',
            'descripcion' => 'Se modificó el total de Depreciación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoDepreciacion', $resp->getTotalDepreciacion());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalDepreciacion", name="aprobarTotalDepreciacion")
     */
    public function aprobarTotalDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalDepreciacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de Depreciación',
            'descripcion' => 'Se aprobó el presupuesto de Depreciación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoDepreciacion', $resp->getAprobarPrespuestoTotalDepreciacion());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalBonificacion", name="insertTotalBonificacion")
     */
    public function insertTotalBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalBonificacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Bonificación',
            'descripcion' => 'Se insertó el total de Bonificación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoBonificacion', $resp->getTotalBonificacion());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalBonificacion", name="updateTotalBonificacion")
     */
    public function updateTotalBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalBonificacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Bonificación',
            'descripcion' => 'Se modificó el total de Bonificación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoBonificacion', $resp->getTotalBonificacion());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalBonificacion", name="aprobarTotalBonificacion")
     */
    public function aprobarTotalBonificacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalBonificacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de Bonificación',
            'descripcion' => 'Se aprobó el presupuesto de Bonificación del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoBonificacion', $resp->getAprobarPrespuestoTotalBonificacion());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalComedor", name="insertTotalComedor")
     */
    public function insertTotalComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalComedor($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de gastos de comedor',
            'descripcion' => 'Se insertó el total de gastos de comedor del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoComedor', $resp->getTotalComedor());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalComedor", name="updateTotalComedor")
     */
    public function updateTotalComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalComedor($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de gastos de comedor',
            'descripcion' => 'Se modificó el total de gastos de comedor del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoComedor', $resp->getTotalComedor());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalComedor", name="aprobarTotalComedor")
     */
    public function aprobarTotalComedorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalComedor($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de gastos de comedor',
            'descripcion' => 'Se aprobó el presupuesto de gastos de comedor del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoComedor', $resp->getAprobarPrespuestoTotalComedor());
        return new Response('ok');
    }

    /**
     * @Route("/insertTotalAmortizacion", name="insertTotalAmortizacion")
     */
    public function insertTotalAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->agregarTotalAmortizacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar total de Amortización',
            'descripcion' => 'Se insertó el total de Amortización del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoAmortizacion', $resp->getTotalAmortizacion());
        return new Response('ok');
    }

    /**
     * @Route("/updateTotalAmortizacion", name="updateTotalAmortizacion")
     */
    public function updateTotalAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')

        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->modificarTotalAmortizacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total de Amortización',
            'descripcion' => 'Se modificó el total de Amortización del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('totalEstimadoAmortizacion', $resp->getTotalAmortizacion());
        return new Response('ok');
    }

    /**
     * @Route("/aprobarTotalAmortizacion", name="aprobarTotalAmortizacion")
     */
    public function aprobarTotalAmortizacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalAmortizacion($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza', array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar presupuesto de Amortización',
            'descripcion' => 'Se aprobó el presupuesto de Amortización del año: ' . $resp->getYear()
        ));
        $session = new Session();
        $session->set('aprobarEstimadoAmortizacion', $resp->getAprobarPrespuestoTotalAmortizacion());
        return new Response('ok');
    }

    /**
     * @Route("/inicializarProcesosPlan", name="inicializarProcesosPlan")
     */
    public function inicializarProcesosPlanAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $data = array(
            'ventas' => $peticion->request->get('ventas'),
            'otrosIngresos' => $peticion->request->get('otrosIngresos'),
            'salario' => $peticion->request->get('salario'),
            'combustible' => $peticion->request->get('combustible'),
            'aceite' => $peticion->request->get('aceite'),
            'energia' => $peticion->request->get('energia'),
            'otrosGastos' => $peticion->request->get('otrosGastos'),
            'materiasPrimas' => $peticion->request->get('materiasPrimas'),
            'depreciacion' => $peticion->request->get('depreciacion'),
            'bonificacion' => $peticion->request->get('bonificacion'),
            'comedor' => $peticion->request->get('comedor')
        );

        $planesEstimados = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->findOneBy(array('aprobado' => false));
        $inicializar = 'No se ha inicializado ningún plan.';

        if (!empty($planesEstimados)) {
            $inicializar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->inicializarProcesos($data, $planesEstimados->getId());
        }

        return new Response($inicializar);
    }

}
