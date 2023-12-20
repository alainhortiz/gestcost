<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DepreciacionController extends Controller
{
    /**
     * @Route("/gestionarDepreciaciones", name="gestionarDepreciaciones")
     */
    public function gestionarDepreciacionesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $depreciaciones  = $em->getRepository('AppBundle:Depreciacion')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Depreciación',
             'descripcion' => 'Listado de Depreciación'
         ));

        return $this->render('Nomencladores/GestionDepreciacion/gestionDepreciacion.twig' , array('depreciaciones' => $depreciaciones));
    }

    /**
     * @Route("/insertDepreciacion", name="insertDepreciacion")
     */
    public function insertDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );
        $resp = $em->getRepository('AppBundle:Depreciacion')->agregarDepreciacion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Depreciación',
            'descripcion' => 'Se insertó una nueva Depreciación con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateDepreciacion", name="updateDepreciacion")
     */
    public function updateDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idDepreciacion' => $peticion->request->get('idDepreciacion'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );

        $resp = $em->getRepository('AppBundle:Depreciacion')->modificarDepreciacion($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Depreciación',
            'descripcion' => 'Se modificó la Depreciación:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteDepreciacion", name="deleteDepreciacion")
     */
    public function deleteDepreciacionAction()
    {
        $peticion = Request::createFromGlobals();
        $idDepreciacion = $peticion->request->get('idDepreciacion');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Depreciacion')->eliminarDepreciacion($idDepreciacion);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Depreciación',
            'descripcion' => 'Se eliminó la Depreciación: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
