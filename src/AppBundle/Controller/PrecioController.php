<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrecioController extends Controller
{
    /**
     * @Route("/gestionarPrecioCombustible/{idTipoCombustible}", name="gestionarPrecioCombustible")
     */
    public function gestionarPrecioCombustibleAction($idTipoCombustible)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tipoCombustible  = $em->getRepository('AppBundle:TipoCombustible')->find($idTipoCombustible);
        $preciosCombustibles  = $em->getRepository('AppBundle:PrecioCombustible')->findBy(array('tipoCombustible' => $idTipoCombustible));
        $mesesTransportes = $this->meses();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Precios de Combustibles',
            'descripcion' => 'Listado de Precios del  Combustible ' . $tipoCombustible->getNombre()
        ));

        return $this->render('Nomencladores/GestionPrecio/gestionPrecioCombustible.twig' , array(
            'tipoCombustible' => $tipoCombustible,
            'preciosCombustibles' => $preciosCombustibles,
            'mesesTransportes' => $mesesTransportes));
    }

    /**
     * @Route("/addPrecioCombustible/{idTipoCombustible}", name="addPrecioCombustible")
     */
    public function addPrecioCombustibleAction($idTipoCombustible)
    {
        $em = $this->getDoctrine()->getManager();
        $tipoCombustible  = $em->getRepository('AppBundle:TipoCombustible')->find($idTipoCombustible);
        $mesesTransportes = $this->meses();

        return $this->render('Nomencladores/GestionPrecio/addPrecioCombustible.html.twig' ,
            array('tipoCombustible' => $tipoCombustible,
                'mesesTransportes' => $mesesTransportes));
    }

    /**
     * @Route("/insertPrecioCombustible", name="insertPrecioCombustible")
     */
    public function insertPrecioCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $tipoCombustible  = $em->getRepository('AppBundle:TipoCombustible')->find($peticion->request->get('idTipoCombustible'));

        $data = array(
            'year' => $peticion->request->get('year'),
            'idTipoCombustible' => $peticion->request->get('idTipoCombustible'),
            'preciosMeses' => $peticion->request->get('preciosMeses')
        );

        $resp = $em->getRepository('AppBundle:PrecioCombustible')->agregarPrecioCombustible($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Precio de Combustible',
            'descripcion' => 'Se insertó el precio para el Tipo de Combustible : '.$tipoCombustible->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updatePrecioCombustible", name="updatePrecioCombustible")
     */
    public function updatePrecioCombustibleAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'year' => $peticion->request->get('year'),
            'mes' => $peticion->request->get('mes'),
            'precio' => $peticion->request->get('precio'),
            'idTipoCombustible' => $peticion->request->get('idTipoCombustible')
        );

        $tipoCombustible  = $em->getRepository('AppBundle:TipoCombustible')->find($peticion->request->get('idTipoCombustible'));

        $resp = $em->getRepository('AppBundle:PrecioCombustible')->modificarPrecioCombustible($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar precio del combustible',
            'descripcion' => 'Se modificó el precio del Tipo de Combustible:  '.$tipoCombustible->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deletePrecioCombustible", name="deletePrecioCombustible")
     */
    public function deletePrecioCombustibleAction()
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

    /**
     * @Route("/gestionarPrecioLubricante/{idTipoAceite}", name="gestionarPrecioLubricante")
     */
    public function gestionarPrecioLubricanteAction($idTipoAceite)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tipoAceite  = $em->getRepository('AppBundle:Lubricante')->find($idTipoAceite);
        $preciosAceite  = $em->getRepository('AppBundle:PrecioLubricante')->findBy(array('lubricante' => $idTipoAceite));
        $mesesTransportes = $this->meses();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Precios de Aceites',
            'descripcion' => 'Listado de Precios del Aceite ' . $tipoAceite->getNombre()
        ));

        return $this->render('Nomencladores/GestionPrecio/gestionPrecioLubricante.twig' , array(
            'tipoAceite' => $tipoAceite,
            'preciosAceite' => $preciosAceite,
            'mesesTransportes' => $mesesTransportes));
    }

    /**
     * @Route("/addPrecioLubricante/{idTipoAceite}", name="addPrecioLubricante")
     */
    public function addPrecioLubricanteAction($idTipoAceite)
    {
        $em = $this->getDoctrine()->getManager();
        $tipoAceite  = $em->getRepository('AppBundle:Lubricante')->find($idTipoAceite);
        $mesesTransportes = $this->meses();

        return $this->render('Nomencladores/GestionPrecio/addPrecioAceite.html.twig' ,
            array('tipoAceite' => $tipoAceite,
                'mesesTransportes' => $mesesTransportes));
    }

    /**
     * @Route("/insertPrecioAceite", name="insertPrecioAceite")
     */
    public function insertPrecioAceiteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $tipoAceite  = $em->getRepository('AppBundle:Lubricante')->find($peticion->request->get('idTipoAceite'));

        $data = array(
            'year' => $peticion->request->get('year'),
            'idTipoAceite' => $peticion->request->get('idTipoAceite'),
            'preciosMeses' => $peticion->request->get('preciosMeses')
        );

        $resp = $em->getRepository('AppBundle:PrecioCombustible')->agregarPrecioAceite($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Precio de Aceite',
            'descripcion' => 'Se insertó el precio para el Tipo de Aceite: '.$tipoAceite->getNombre()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updatePrecioAceite", name="updatePrecioAceite")
     */
    public function updatePrecioAceiteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'year' => $peticion->request->get('year'),
            'mes' => $peticion->request->get('mes'),
            'precio' => $peticion->request->get('precio'),
            'idTipoAceite' => $peticion->request->get('idTipoAceite')
        );

        $tipoAceite  = $em->getRepository('AppBundle:Lubricante')->find($peticion->request->get('idTipoAceite'));

        $resp = $em->getRepository('AppBundle:PrecioCombustible')->modificarPrecioAceite($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar precio del Aceite',
            'descripcion' => 'Se modificó el precio del Tipo de Aceite:  '.$tipoAceite->getNombre()
        ));
        return new Response('ok');
    }

    private function meses() {
        return array(
            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'
        );
    }
}
