<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoTransporteController extends Controller
{
    /**
     * @Route("/gestionarTiposTransportes", name="gestionarTiposTransportes")
     */
    public function gestionarTiposTransportesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tiposTransportes  = $em->getRepository('AppBundle:TipoTransporte')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Tipos de Transportes',
             'descripcion' => 'Listado de Tipos de Transportes'
         ));

        return $this->render('Nomencladores/GestionTipoTransporte/gestionTipoTransporte.twig' , array(
            'tiposTransportes' => $tiposTransportes

        ));
    }

    /**
     * @Route("/insertTipoTransporte", name="insertTipoTransporte")
     */
    public function insertTipoTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );
        $resp = $em->getRepository('AppBundle:TipoTransporte')->agregarTipoTransporte($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Tipo de Transporte',
            'descripcion' => 'Se insertó un Tipo de transporte con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTipoTransporte", name="updateTipoTransporte")
     */
    public function updateTipoTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idTipoTransporte' => $peticion->request->get('idTipoTransporte'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:TipoTransporte')->modificarTipoTransporte($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Tipo de Transporte',
            'descripcion' => 'Se modificó el Tipo de Transporte:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteTipoTransporte", name="deleteTipoTransporte")
     */
    public function deleteTipoTransporteAction()
    {
        $peticion = Request::createFromGlobals();
        $idTipoTransporte = $peticion->request->get('idTipoTransporte');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:TipoTransporte')->eliminarTipoTransporte($idTipoTransporte);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Tipo de Transporte',
            'descripcion' => 'Se eliminó el Tipo de Transporte: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
