<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CentroCostoController extends Controller
{
    /**
     * @Route("/gestionarCentrosCostos", name="gestionarCentrosCostos")
     */
    public function gestionarCentrosCostosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();
        $provincias  = $em->getRepository('AppBundle:Provincia')->findBy(array(),array('id' => 'ASC'));

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Centros de Costos',
            'descripcion' => 'Listado de Centros de Costos'
        ));

        return $this->render('Nomencladores/GestionCentroCosto/gestionCentroCosto.twig' , array(
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'provincias' => $provincias
            ));
    }

    /**
     * @Route("/insertCentroCosto", name="insertCentroCosto")
     */
    public function insertCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();



        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'provincia' => $peticion->request->get('provincia'),
            'division' => $peticion->request->get('division'),
            'activo' => $peticion->request->get('activo'),
        );

        $resp = $em->getRepository('AppBundle:CentroCosto')->agregarCentroCosto($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Centro de Costo',
            'descripcion' => 'Se insertó un nuevo Centro de Costo con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateCentroCosto", name="updateCentroCosto")
     */
    public function updateCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idCentroCosto' => $peticion->request->get('idCentroCosto'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'provincia' => $peticion->request->get('provincia'),
            'division' => $peticion->request->get('division'),
            'activo' => $peticion->request->get('activo')
        );

        $resp = $em->getRepository('AppBundle:CentroCosto')->modificarCentroCosto($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Centro de Costo',
            'descripcion' => 'Se modificó el Centro de Costo:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteCentroCosto", name="deleteCentroCosto")
     */
    public function deleteCentroCostoAction()
    {
        $peticion = Request::createFromGlobals();
        $idCentroCosto = $peticion->request->get('idCentroCosto');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:CentroCosto')->eliminarCentroCosto($idCentroCosto);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Centro de Costo',
            'descripcion' => 'Se eliminó el Centro de Costo: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
