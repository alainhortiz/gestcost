<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PromedioTrabajadorController extends Controller
{
    /**
     * @Route("/gestionarPromedioTrabajadores", name="gestionarPromedioTrabajadores")
     */
    public function gestionarPromedioTrabajadoresAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $promedioTrabajadores  = $em->getRepository('AppBundle:PromedioTrabajador')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Promedio de Trabajadores',
            'descripcion' => 'Total de promedio de trabajadores'
        ));

        return $this->render('Nomencladores/GestionPromedioTrabajador/gestionPromedioTrabajador.twig' , array('promedioTrabajadores' => $promedioTrabajadores));
    }

    /**
     * @Route("/updatePromedioTrabajador", name="updatePromedioTrabajador")
     */
    public function updatePromedioTrabajadorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPromedioTrabajador' => $peticion->request->get('idPromedioTrabajador'),
            'total' => $peticion->request->get('total'),
        );

        $resp = $em->getRepository('AppBundle:PromedioTrabajador')->modificarPromedioTrabajador($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar total del promedio de trabajadores',
            'descripcion' => 'Se modificó el total del promedio de trabajadores:  '.$data['total']
        ));
        return new Response('ok');
    }
}

