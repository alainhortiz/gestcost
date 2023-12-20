<?php /** @noinspection SyntaxError */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanEstimadoOtrosGastosController extends Controller
{
    /**
     * @Route("/gestionarEstimadoPlanOtrosGastos", name="gestionarEstimadoPlanOtrosGastos")
     */
    public function gestionarEstimadoPlanOtrosGastosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $otrosGastos  = $em->getRepository('AppBundle:OtroGasto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');

        $activo = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->yearActivo();
        $totalPresupuestoEstimadoOtroGasto = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->totalPresupuestoEstimadoOtroGasto($planEstimado);
        $totalEstimadoOtroGasto = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->totalEstimadoOtroGasto($planEstimado);
        $cantidadEstimadoOtroGasto = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->cantidadEstimadoOtroGasto($planEstimado);
        $cantidadEstimadoMesOtroGasto = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->cantidadEstimadoMesOtroGasto($planEstimado);

        if(!$totalPresupuestoEstimadoOtroGasto) {
            $totalPresupuestoEstimadoOtroGasto = 0;
        }

        if(empty($totalEstimadoOtroGasto)) {
            $totalEstimadoOtroGasto = 0;
            $porcientoMensual = 0;
            $aprobarEstimadoOtroGasto = false;
        }else {
            $porcientoMensual = (int)(($cantidadEstimadoMesOtroGasto / $cantidadEstimadoOtroGasto) * 100);
            $aprobarEstimadoOtroGasto = $activo[0]->getAprobarPrespuestoOtrosGastos();
        }

        $graficosTotalesEstimadosOtrosGastos  = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->graficoTotalesEstimadosOtrosGastos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Division Energía - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Energía por división'
        ));

        return $this->render('GestionPlanEstimado/GestionOtrosGastos/gestionOtrosGastos.html.twig', array(
            'otrosGastos' => $otrosGastos,
            'totalPresupuestoEstimadoOtroGasto' => $totalPresupuestoEstimadoOtroGasto,
            'totalEstimadoOtroGasto' => $totalEstimadoOtroGasto,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'aprobarEstimadoOtroGasto' => $aprobarEstimadoOtroGasto,
            'graficosTotalesEstimadosOtrosGastos' => $graficosTotalesEstimadosOtrosGastos,
            'porcientoMensual' => $porcientoMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanOtrosGastosDivision", name="gestionarEstimadoPlanOtrosGastosDivision")
     */
    public function gestionarEstimadoPlanOtrosGastosDivisionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');

        $otrosGastos  = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->otrosGastosActivos($planEstimado);

        $serviciosAprobados  = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->verificarAprobadoEstimadoOtroGastoDivisionMes($planEstimado);

        $graficosOtroGastoEstimadoMensual  = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->graficosOtroGastoEstimadoMensualTodos($planEstimado);

        $graficosOtroGastoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->graficosOtroGastoEstimadoDivisionMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan División Otros Gastos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Otros Gastos por Divisiones'
        ));

        return $this->render('GestionPlanEstimado/GestionOtrosGastos/gestionOtrosGastosDivision.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'serviciosAprobados' => $serviciosAprobados,
            'otrosGastos' => $otrosGastos,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'graficosOtroGastoEstimadoMensual' => $graficosOtroGastoEstimadoMensual,
            'graficosOtroGastoEstimadoDivisionMensual' => $graficosOtroGastoEstimadoDivisionMensual
        ));

    }

    /**
     * @Route("/gestionarEstimadoPlanOtrosGastosCentroCosto", name="gestionarEstimadoPlanOtrosGastosCentroCosto")
     */
    public function gestionarEstimadoPlanOtrosGastosCentroCostoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findBy(array('isActive' => true));

        $session = new Session();
        $planEstimado = $session->get('idPlanEstimado');
        $yearActivo = $session->get('yearActivo');
        $totalEstimadoEnergia = $session->get('totalEstimadoEnergiaDivision');

        $divisionCentrosCostos  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->divisionOtrosGastosActivos($planEstimado);

        $otrosGastos  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->otrosGastosDivisionActivos($planEstimado);

        $serviciosAprobados  = $em->getRepository('AppBundle:PlanEstimadoDivisionOtrosGastos')->verificarAprobadoEstimadoOtroGastoDivisionMes($planEstimado);

        $graficosOtroGastoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->graficosOtroGastoEstimadoDivisionMensualTodos($planEstimado);

        $graficosOtroGastoEstimadoDivisionAgrupado  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->otroGastoEstimadoDivisionAgrupado($planEstimado);

        $graficosOtroGastoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->graficosOtroGastoEstimadoCentroCostoMensualTodos($planEstimado);

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Plan Centro Costo Otros Gastos - Gestionar Plan',
            'descripcion' => 'Gestionar el plan de Otros Gastos por Centro de Costos'
        ));

        return $this->render('GestionPlanEstimado/GestionOtrosGastos/gestionOtrosGastosCentroCosto.html.twig', array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'otrosGastos' => $otrosGastos,
            'totalEstimadoEnergia' => $totalEstimadoEnergia,
            'planEstimado' => $planEstimado,
            'yearActivo' => $yearActivo,
            'serviciosAprobados' => $serviciosAprobados,
            'graficosOtroGastoEstimadoDivisionMensual' => $graficosOtroGastoEstimadoDivisionMensual,
            'graficosOtroGastoEstimadoDivisionAgrupado' => $graficosOtroGastoEstimadoDivisionAgrupado,
            'graficosOtroGastoEstimadoCentroCostoMensual' => $graficosOtroGastoEstimadoCentroCostoMensual
        ));

    }

    /**
     * @Route("/insertTotalEstimadoOtroGasto", name="insertTotalEstimadoOtroGasto")
     */
    public function insertTotalEstimadoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->masterAgregarEstimadoOtroGasto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/updateTotalEstimadoOtroGasto", name="updateTotalEstimadoOtroGasto")
     */
    public function updateTotalEstimadoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->masterModificadorEstimadoOtroGasto($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/insertTotalEstimadoOtroGastoMes", name="insertTotalEstimadoOtroGastoMes")
     */
    public function insertTotalEstimadoOtroGastoMesAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'idOtroGasto' => $peticion->request->get('idOtroGasto')
        );

        $resp  =  $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->masterAgregarEstimadoOtroGastoMes($data,$user);

        // verificar si todas los servicios con presupuesto estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->verificarAprobadoEstimadoOtrosGastosMeses($data);

        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoMesOtroGasto($data);
            $session = new Session();
            $session->set('aprobarEstimadoOtrosGastosDivision', true);

            if(is_string($aprobar)) {
                return new Response($aprobar);
            }
        }

        return new Response($resp);
    }

    /**
     * @Route("/graficoOtroGastoEstimadoMensual", name="graficoOtroGastoEstimadoMensual")
     */
    public function graficoOtroGastoEstimadoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $otroGasto = $peticion->request->get('otroGasto');

        $graficosOtroGastoEstimadoMensual  = $em->getRepository('AppBundle:PlanEstimadoMesOtrosGastos')->graficosOtroGastoEstimadoMensual($idPlanEstimado,$otroGasto);

        if(is_string($graficosOtroGastoEstimadoMensual)) {
            return new Response($graficosOtroGastoEstimadoMensual);
        }

        $result = json_encode($graficosOtroGastoEstimadoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoOtroGastoEstimadoDivisionMensual", name="graficoOtroGastoEstimadoDivisionMensual")
     */
    public function graficoOtroGastoEstimadoDivisionMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $otroGasto = $peticion->request->get('otroGasto');
        $division = $peticion->request->get('division');

        $graficosOtroGastoEstimadoDivisionMensual  = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->graficosOtroGastoEstimadoDivisionMensual($idPlanEstimado, $otroGasto, $division);

        if(is_string($graficosOtroGastoEstimadoDivisionMensual)) {
            return new Response($graficosOtroGastoEstimadoDivisionMensual);
        }

        $result = json_encode($graficosOtroGastoEstimadoDivisionMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoDivisionOtroGasto", name="insertTotalEstimadoDivisionOtroGasto")
     */
    public function insertTotalEstimadoDivisionOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $session = new Session();
        $session->set('servicio', $peticion->request->get('otroGasto'));
        $session->set('mes', $peticion->request->get('mes'));

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->masterAgregarEstimadoOtroGastoDivision($data,$user);

        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoDivisionOtroGasto", name="updateTotalEstimadoDivisionOtroGasto")
     */
    public function updateTotalEstimadoDivisionOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->masterModificadorEstimadoOtroGastoDivision($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoDivisionOtroGasto", name="aprobarPresupuestoEstimadoDivisionOtroGasto")
     */
    public function aprobarPresupuestoEstimadoDivisionOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->aprobarTotalEstimadoDivisionOtroGasto($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Aprobar el presupuesto mensual de este servicio para las divisiones',
            'descripcion' => 'Se aprobó el presupuesto mensual para las divisiones en el servicio: '.$resp->getOtroGasto()->getNombre()
        ));

        // verificar si todas los servicios con divisiones estan aprobadas
        $verificar = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->verificarAprobadoOtroGastoDivisionMeses($data);
        if ($verificar === 0) {
            $aprobar = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->aprobarTotalEstimadoDivisionMesOtroGasto($data);
            $session = new Session();
            $session->set('aprobarEstimadoOtrosGastosCentroCosto', true);

            if (is_string($aprobar)) {
                return new Response($aprobar);
            }
        }
        return new Response('ok');
    }

    /**
     * @Route("/dashboardOtrosGastosEstimadoMensual", name="dashboardOtrosGastosEstimadoMensual")
     */
    public function dashboardOtrosGastosEstimadoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $otroGasto = $peticion->request->get('otroGasto');

        $graficosOtroGastoEstimadoDivision = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->graficosOtroGastoEstimadoDivisionAgrupado($idPlanEstimado, $otroGasto);
        $graficosEstimadosOtrosGastosMensual = $em->getRepository('AppBundle:PlanEstimadoDivisionMesOtrosGastos')->graficosOtroGastoEstimadoMesAgrupado($idPlanEstimado, $otroGasto);

        $graficos = array(
            'servicioDivision' => $graficosOtroGastoEstimadoDivision,
            'servicioMensual' => $graficosEstimadosOtrosGastosMensual
        );

        if (is_string($graficosOtroGastoEstimadoDivision)) {
            return new Response($graficosOtroGastoEstimadoDivision);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoOtroGastoEstimadoCentroCostoMensual", name="graficoOtroGastoEstimadoCentroCostoMensual")
     */
    public function graficoOtroGastoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $otroGasto = $peticion->request->get('otroGasto');
        $centroCosto = $peticion->request->get('centroCosto');

        $graficosOtroGastoEstimadoCentroCostoMensual  = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->graficosOtroGastoEstimadoCentroCostoMensual($idPlanEstimado,$otroGasto,$centroCosto);

        if(is_string($graficosOtroGastoEstimadoCentroCostoMensual)) {
            return new Response($graficosOtroGastoEstimadoCentroCostoMensual);
        }

        $result = json_encode($graficosOtroGastoEstimadoCentroCostoMensual);
        return new JsonResponse($result);

    }

    /**
     * @Route("/insertTotalEstimadoCentroCostoOtroGasto", name="insertTotalEstimadoCentroCostoOtroGasto")
     */
    public function insertTotalEstimadoCentroCostoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->masterAgregarEstimadoOtroGastoCentroCosto($data,$user);


        return new Response($resp);
    }

    /**
     * @Route("/updateTotalEstimadoCentroCostoOtroGasto", name="updateTotalEstimadoCentroCostoOtroGasto")
     */
    public function updateTotalEstimadoCentroCostoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'presupuesto' => $peticion->request->get('presupuesto'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'division' => $peticion->request->get('division'),
            'mes' => $peticion->request->get('mes')
        );

        $resp = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->masterModificadorEstimadoOtroGastoCentroCosto($data,$user);

        return new Response($resp);

    }

    /**
     * @Route("/aprobarPresupuestoEstimadoCentroCostoOtroGasto", name="aprobarPresupuestoEstimadoCentroCostoOtroGasto")
     */
    public function aprobarPresupuestoEstimadoCentroCostoOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlanEstimado' => $peticion->request->get('idPlanEstimado'),
            'otroGasto' => $peticion->request->get('otroGasto'),
            'division' => $peticion->request->get('division'),
            'presupuesto' => $peticion->request->get('presupuesto')
        );

        //Agregar nuevo registro al PlanEstimaDivisionOtroGasto
        $resp = $em->getRepository('AppBundle:PlanEstimadoOtrosGastos')->masterAprobarTotalEstimadoCentroCostoOtroGasto($data, $user);

        return new Response($resp);
    }

    /**
     * @Route("/dashboardOtroGastoEstimadoCentroCostoMensual", name="dashboardOtroGastoEstimadoCentroCostoMensual")
     */
    public function dashboardOtroGastoEstimadoCentroCostoMensualAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $idPlanEstimado = $peticion->request->get('idPlanEstimado');
        $division = $peticion->request->get('division');
        $otroGasto = $peticion->request->get('otroGasto');

        $graficosOtroGastoEstimadoCentroCosto = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->graficosOtroGastoEstimadoCentroCostoAgrupado($idPlanEstimado, $division, $otroGasto);
        $graficosOtroGastoEstimadoCentroCostoMensual = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesOtrosGastos')->graficosOtroGastoEstimadoCentroCostoMensualAgrupado($idPlanEstimado, $division, $otroGasto);

        $graficos = array(
            'servicioCentroCosto' => $graficosOtroGastoEstimadoCentroCosto,
            'servicioCentroCostoMensual' => $graficosOtroGastoEstimadoCentroCostoMensual
        );

        if (is_string($graficosOtroGastoEstimadoCentroCosto)) {
            return new Response($graficosOtroGastoEstimadoCentroCosto);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

}

