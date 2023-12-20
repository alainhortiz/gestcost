<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OtrosGastosController extends Controller
{
    /**
     * @Route("/gestionarOtrosGastos", name="gestionarOtrosGastos")
     */
    public function gestionarOtrosGastosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tiposServicios  = $em->getRepository('AppBundle:TipoServicio')->findAll();
        $otrosGastos  = $em->getRepository('AppBundle:OtroGasto')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Otros Gastos',
             'descripcion' => 'Listado de Otros Gastos'
         ));

        return $this->render('Nomencladores/GestionOtrosGastos/gestionOtroGasto.twig' , array(
            'otrosGastos' => $otrosGastos,
            'tiposServicios' => $tiposServicios
        ));
    }

    /**
     * @Route("/insertOtroGasto", name="insertOtroGasto")
     */
    public function insertOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'tipoServicio' => $peticion->request->get('tipoServicio'),
            'activo' => $peticion->request->get('activo')
        );
        $resp = $em->getRepository('AppBundle:OtroGasto')->agregarOtroGasto($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Otros Gastos',
            'descripcion' => 'Se insertó Otro Gasto con el nombre: '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateOtroGasto", name="updateOtroGasto")
     */
    public function updateOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idOtroGasto' => $peticion->request->get('idOtroGasto'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'tipoServicio' => $peticion->request->get('tipoServicio'),
            'activo' => $peticion->request->get('activo')
        );

        $resp = $em->getRepository('AppBundle:OtroGasto')->modificarOtroGasto($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Otros Gastos',
            'descripcion' => 'Se modificó Otro Gasto:  '.$data['nombre']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteOtroGasto", name="deleteOtroGasto")
     */
    public function deleteOtroGastoAction()
    {
        $peticion = Request::createFromGlobals();
        $idOtroGasto = $peticion->request->get('idOtroGasto');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:OtroGasto')->eliminarOtroGasto($idOtroGasto);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Otros Gastos',
            'descripcion' => 'Se eliminó Otro Gasto: '.$resp->getNombre()
        ));
        return new Response('ok');
    }
}
