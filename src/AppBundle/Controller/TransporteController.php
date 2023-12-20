<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransporteController extends Controller
{
    /**
     * @Route("/gestionarTransportes", name="gestionarTransportes")
     */
    public function gestionarTransportesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();
        $modelosTransportes  = $em->getRepository('AppBundle:ModeloTransporte')->findAll();
        $tiposCombustibles  = $em->getRepository('AppBundle:TipoCombustible')->findAll();
        $tiposLubricantes  = $em->getRepository('AppBundle:Lubricante')->findAll();
        $transportes  = $em->getRepository('AppBundle:Transporte')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Medios de Transportes',
             'descripcion' => 'Listado de Medios de Transportes'
         ));

        return $this->render('Nomencladores/GestionTransporte/gestionTransporte.twig' , array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'modelosTransportes' => $modelosTransportes,
            'tiposCombustibles' => $tiposCombustibles,
            'tiposLubricantes' => $tiposLubricantes,
            'transportes' => $transportes
        ));
    }

    /**
     * @Route("/addTransporte", name="addTransporte")
     */
    public function addTransporteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();
        $modelosTransportes  = $em->getRepository('AppBundle:ModeloTransporte')->findAll();
        $tiposCombustibles  = $em->getRepository('AppBundle:TipoCombustible')->findAll();
        $tiposLubricantes  = $em->getRepository('AppBundle:Lubricante')->findAll();
        $transportes  = $em->getRepository('AppBundle:Transporte')->findAll();

        return $this->render('Nomencladores/GestionTransporte/addTransporte.html.twig' , array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'modelosTransportes' => $modelosTransportes,
            'tiposCombustibles' => $tiposCombustibles,
            'tiposLubricantes' => $tiposLubricantes,
            'transportes' => $transportes
        ));
    }

    /**
     * @Route("/insertTransporte", name="insertTransporte")
     */
    public function insertTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'matricula' => $peticion->request->get('matricula'),
            'noActivoFijo' => $peticion->request->get('noActivoFijo'),
            'year' => $peticion->request->get('year'),
            'color' => $peticion->request->get('color'),
            'chasis' => $peticion->request->get('chasis'),
            'motor' => $peticion->request->get('motor'),
            'valor' => $peticion->request->get('valor'),
            'circulacion' => $peticion->request->get('circulacion'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'modeloTransporte' => $peticion->request->get('modeloTransporte'),
            'tipoCombustible' => $peticion->request->get('tipoCombustible'),
            'tipoLubricante' => $peticion->request->get('tipoLubricante'),
            'lubricante' => $peticion->request->get('lubricante'),
            'activo' => $peticion->request->get('activo')
        );

        $resp = $em->getRepository('AppBundle:Transporte')->agregarTransporte($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Medio de Transporte',
            'descripcion' => 'Se insertó un nuevo Medio de Transporte con el modelo: '.$data['modeloTransporte']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/editTransporte/{idTransporte}", name="editTransporte")
     */
    public function editTransporteAction($idTransporte)
    {
        $em = $this->getDoctrine()->getManager();
        $transporte = $em->getRepository('AppBundle:Transporte')->find($idTransporte);
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();
        $modelosTransportes  = $em->getRepository('AppBundle:ModeloTransporte')->findAll();
        $tiposCombustibles  = $em->getRepository('AppBundle:TipoCombustible')->findAll();
        $tiposLubricantes  = $em->getRepository('AppBundle:Lubricante')->findAll();

        return $this->render('Nomencladores/GestionTransporte/editTransporte.html.twig' , array(
            'transporte' => $transporte,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'modelosTransportes' => $modelosTransportes,
            'tiposCombustibles' => $tiposCombustibles,
            'tiposLubricantes' => $tiposLubricantes,
        ));
    }

    /**
     * @Route("/updateTransporte", name="updateTransporte")
     */
    public function updateTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idTransporte' => $peticion->request->get('idTransporte'),
            'matricula' => $peticion->request->get('matricula'),
            'noActivoFijo' => $peticion->request->get('noActivoFijo'),
            'year' => $peticion->request->get('year'),
            'color' => $peticion->request->get('color'),
            'chasis' => $peticion->request->get('chasis'),
            'motor' => $peticion->request->get('motor'),
            'valor' => $peticion->request->get('valor'),
            'circulacion' => $peticion->request->get('circulacion'),
            'centroCosto' => $peticion->request->get('centroCosto'),
            'modeloTransporte' => $peticion->request->get('modeloTransporte'),
            'tipoCombustible' => $peticion->request->get('tipoCombustible'),
            'tipoLubricante' => $peticion->request->get('tipoLubricante'),
            'lubricante' => $peticion->request->get('lubricante')
        );

        $resp = $em->getRepository('AppBundle:Transporte')->modificarTransporte($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Medio de Transporte',
            'descripcion' => 'Se modificó el Medio de Transporte:  '.$data['modeloTransporte']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteTransporte", name="deleteTransporte")
     */
    public function deleteTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $idTransporte = $peticion->request->get('idTransporte');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Transporte')->eliminarTransporte($idTransporte);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Medio de Transporte',
            'descripcion' => 'Se eliminó el Medio de Transporte: ' . $resp->getModeloTransporte()->getNombre()
        ));
        return new Response('ok');
    }
}
