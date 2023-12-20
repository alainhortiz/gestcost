<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoCombustibleController extends Controller
{
    /**
     * @Route("/gestionarTiposCombustibles", name="gestionarTiposCombustibles")
     */
    public function gestionarTiposCombustiblesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tiposCombustibles  = $em->getRepository('AppBundle:TipoCombustible')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Tipos de Combustibles',
             'descripcion' => 'Listado de Tipos de Combustibles'
         ));

        return $this->render('Nomencladores/GestionTipoCombustible/gestionTipoCombustible.twig' , array('tiposCombustibles' => $tiposCombustibles));
    }

    /**
     * @Route("/insertTipoCombustible", name="insertTipoCombustible")
     */
    public function insertTipoCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );
        $resp = $em->getRepository('AppBundle:TipoCombustible')->agregarTipoCombustible($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Tipos de Combustibles',
            'descripcion' => 'Se insertó el Tipo de Combustible con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateTipoCombustible", name="updateTipoCombustible")
     */
    public function updateTipoCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idTipoCombustible' => $peticion->request->get('idTipoCombustible'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );

        $resp = $em->getRepository('AppBundle:TipoCombustible')->modificarTipoCombustible($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Tipos de Combustibles',
            'descripcion' => 'Se modificó el Tipo de Combustible:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteTipoCombustible", name="deleteTipoCombustible")
     */
    public function deleteTipoCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $idTipoCombustible = $peticion->request->get('idTipoCombustible');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:TipoCombustible')->eliminarOtroGasto($idTipoCombustible);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Tipos de Combustibles',
            'descripcion' => 'Se eliminó el Tipo de Combustible: '.$resp->getNombre()
        ));
        return new Response('ok');
    }

}
