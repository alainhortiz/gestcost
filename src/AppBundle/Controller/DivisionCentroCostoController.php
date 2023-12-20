<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DivisionCentroCostoController extends Controller
{
    /**
     * @Route("/gestionarDivisionCentrosCostos", name="gestionarDivisionCentrosCostos")
     */
    public function gestionarDivisionCentrosCostosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Divisiones de los Centros de Costos',
            'descripcion' => 'Listado de Centros de Costos'
        ));

        return $this->render('Nomencladores/GestionDivisionCentroCosto/gestionDivisionCentroCosto.twig' , array('divisionCentrosCostos' => $divisionCentrosCostos));
    }

    /**
     * @Route("/insertDivisionCentroCosto", name="insertDivisionCentroCosto")
     */
    public function insertDivisionCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre')
        );
        $resp = $em->getRepository('AppBundle:DivisionCentroCosto')->agregarDivisionCentroCosto($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar División del Centro de Costo',
            'descripcion' => 'Se insertó una nueva División del Centro de Costo con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateDivisionCentroCosto", name="updateDivisionCentroCosto")
     */
    public function updateDivisionCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idDivisionCentroCosto' => $peticion->request->get('idDivisionCentroCosto'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:DivisionCentroCosto')->modificarDivisionCentroCosto($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar división del Centro de Costo',
            'descripcion' => 'Se modificó la división del Centro de Costo:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteDivisionCentroCosto", name="deleteDivisionCentroCosto")
     */
    public function deleteDivisionCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $idDivisionCentroCosto = $peticion->request->get('idDivisionCentroCosto');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:DivisionCentroCosto')->eliminarDivisionCentroCosto($idDivisionCentroCosto);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar División del Centro de Costo',
            'descripcion' => 'Se eliminó la División del Centro de Costo: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
