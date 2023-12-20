<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoServicioController extends Controller
{
    /**
     * @Route("/gestionarTiposServicios", name="gestionarTiposServicios")
     */
    public function gestionarTiposServiciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tiposServicios  = $em->getRepository('AppBundle:TipoServicio')->findAll();

        return $this->render('Nomencladores/GestionTipoServicio/gestionTipoServicio.twig' , array('tiposServicios' => $tiposServicios));
    }

    /**
     * @Route("/insertTipoServicio", name="insertTipoServicio")
     */
    public function insertTipoServicioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre'),
        );
        $resp = $em->getRepository('AppBundle:TipoServicio')->agregarTipoServicio($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Tipo de Servicio',
            'descripcion' => 'Se insertó un tipo de servicio con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTipoServicio", name="updateTipoServicio")
     */
    public function updateTipoServicioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idTipoServicio' => $peticion->request->get('idTipoServicio'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:TipoServicio')->modificarTipoServicio($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar tipo de servicio',
            'descripcion' => 'Se modificó el tipo de servicio:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteTipoServicio", name="deleteTipoServicio")
     */
    public function deleteTipoServicioAction()
    {
        $peticion = Request::createFromGlobals();
        $idTipoServicio = $peticion->request->get('idTipoServicio');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:TipoServicio')->eliminarTipoServicio($idTipoServicio);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Tipo de servicio',
            'descripcion' => 'Se eliminó el tipo de servicio: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
