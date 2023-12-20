<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoPlantillaCargoController extends Controller
{
    /**
     * @Route("/gestionarTiposPlantillas", name="gestionarTiposPlantillas")
     */
    public function gestionarTiposPlantillasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tiposPlantillas  = $em->getRepository('AppBundle:TipoPlantillaCargo')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Divisiones de los Centros de Costos',
            'descripcion' => 'Listado de Centros de Costos'
        ));

        return $this->render('Nomencladores/GestionTipoPlantilla/gestionTipoPlantilla.twig' , array('tiposPlantillas' => $tiposPlantillas));
    }

    /**
     * @Route("/insertTipoPlantilla", name="insertTipoPlantilla")
     */
    public function insertTipoPlantillaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre'),
        );
        $resp = $em->getRepository('AppBundle:TipoPlantillaCargo')->agregarTipoPlantilla($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Tipo de Plantilla',
            'descripcion' => 'Se insertó un nuevo tipo de plantilla con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTipoPlantilla", name="updateTipoPlantilla")
     */
    public function updateTipoPlantillaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idTipoPlantilla' => $peticion->request->get('idTipoPlantilla'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:TipoPlantillaCargo')->modificarTipoPlantilla($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar tipo de plantilla',
            'descripcion' => 'Se modificó el tipo de plantilla:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteTipoPlantilla", name="deleteTipoPlantilla")
     */
    public function deleteTipoPlantillaAction()
    {
        $peticion = Request::createFromGlobals();
        $idTipoPlantilla = $peticion->request->get('idTipoPlantilla');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:TipoPlantillaCargo')->eliminarTipoPlantilla($idTipoPlantilla);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Tipo de Plantilla',
            'descripcion' => 'Se eliminó el tipo de plantilla: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
