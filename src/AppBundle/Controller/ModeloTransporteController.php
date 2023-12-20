<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeloTransporteController extends Controller
{
    /**
     * @Route("/gestionarModelosTransportes", name="gestionarModelosTransportes")
     */
    public function gestionarModelosTransportesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $modelosTransportes  = $em->getRepository('AppBundle:ModeloTransporte')->findAll();
        $tiposTransportes  = $em->getRepository('AppBundle:TipoTransporte')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Modelos de Transportes',
            'descripcion' => 'Listado de Modelos de Transportes'
        ));

        return $this->render('Nomencladores/GestionModeloTransporte/gestionModeloTransporte.twig' , array(
            'modelosTransportes' => $modelosTransportes,
            'tiposTransportes' => $tiposTransportes
        ));
    }

    /**
     * @Route("/insertModeloTransporte", name="insertModeloTransporte")
     */
    public function insertModeloTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre'),
            'tipoTransporte' => $peticion->request->get('tipoTransporte')
        );
        $resp = $em->getRepository('AppBundle:ModeloTransporte')->agregarModeloTransporte($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar  Modelo de Transporte',
            'descripcion' => 'Se insertó un Modelo de Transporte con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateModeloTransporte", name="updateModeloTransporte")
     */
    public function updateModeloTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idModeloTransporte' => $peticion->request->get('idModeloTransporte'),
            'nombre' => $peticion->request->get('nombre'),
            'tipoTransporte' => $peticion->request->get('tipoTransporte')
        );

        $resp = $em->getRepository('AppBundle:ModeloTransporte')->modificarModeloTransporte($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Modelo de Transporte',
            'descripcion' => 'Se modificó el Modelo de Transporte:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteModeloTransporte", name="deleteModeloTransporte")
     */
    public function deleteModeloTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $idModeloTransporte = $peticion->request->get('idModeloTransporte');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:ModeloTransporte')->eliminarModeloTransporte($idModeloTransporte);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Modelo de Transporte',
            'descripcion' => 'Se eliminó el Modelo de Transporte: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
