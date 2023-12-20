<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DenominadorCargoController extends Controller
{
    /**
     * @Route("/gestionarDenominadorCargos", name="gestionarDenominadorCargos")
     */
    public function gestionarDenominadorCargosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $denominadorCargos  = $em->getRepository('AppBundle:DenominadorCargo')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Divisiones de los Centros de Costos',
            'descripcion' => 'Listado de Centros de Costos'
        ));

        return $this->render('Nomencladores/GestionDenominadorCargo/gestionDenominadorCargo.twig' , array('denominadorCargos' => $denominadorCargos));
    }

    /**
     * @Route("/insertDenominadorCargo", name="insertDenominadorCargo")
     */
    public function insertDenominadorCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'denominadorCargo' => $peticion->request->get('denominadorCargo'),
            'grupoEscala' => $peticion->request->get('grupoEscala'),
            'categoria' => $peticion->request->get('categoria'),
            'salario' => $peticion->request->get('salario')
        );

        $resp = $em->getRepository('AppBundle:DenominadorCargo')->agregarDenominadorCargo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Denominador de cargo',
            'descripcion' => 'Se insertó un nuevo denominador de cargo con el nombre: '.$data['denominadorCargo']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateDenominadorCargo", name="updateDenominadorCargo")
     */
    public function updateDenominadorCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idDenominadorCargo' => $peticion->request->get('idDenominadorCargo'),
            'codigo' => $peticion->request->get('codigo'),
            'denominadorCargo' => $peticion->request->get('denominadorCargo'),
            'grupoEscala' => $peticion->request->get('grupoEscala'),
            'categoria' => $peticion->request->get('categoria'),
            'salario' => $peticion->request->get('salario')
        );

        $resp = $em->getRepository('AppBundle:DenominadorCargo')->modificarDenominadorCargo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar denominador de cargo',
            'descripcion' => 'Se modificó el denominador de cargo:  '.$data['denominadorCargo']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteDenominadorCargo", name="deleteDenominadorCargo")
     */
    public function deleteDenominadorCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $idDenominadorCargo = $peticion->request->get('idDenominadorCargo');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:DenominadorCargo')->eliminarDenominadorCargo($idDenominadorCargo);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar denominador de cargo',
            'descripcion' => 'Se eliminó el denominador de cargo: '.$resp->getDenominadorCargo()
        ));
        return new Response('ok');
    }
}
