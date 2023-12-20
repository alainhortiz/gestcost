<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnergiaController extends Controller
{
    /**
     * @Route("/gestionarEnergias", name="gestionarEnergias")
     */
    public function gestionarEnergiasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $energias  = $em->getRepository('AppBundle:Energia')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Unidad de energía',
            'descripcion' => 'Listado de Unidad de energía'
        ));

        return $this->render('Nomencladores/GestionEnergia/gestionEnergia.twig' , array('energias' => $energias));
    }

    /**
     * @Route("/insertEnergia", name="insertEnergia")
     */
    public function insertEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'unidad' => $peticion->request->get('unidad'),
            'precio' => $peticion->request->get('precio')
        );
        $resp = $em->getRepository('AppBundle:Energia')->agregarEnergia($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Unidad de medida de Energía',
            'descripcion' => 'Se insertó una nueva Unidad de medida de Energía con el nombre: '.$data['unidad']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updateEnergia", name="updateEnergia")
     */
    public function updateEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idEnergia' => $peticion->request->get('idEnergia'),
            'unidad' => $peticion->request->get('unidad'),
            'precio' => $peticion->request->get('precio')
        );

        $resp = $em->getRepository('AppBundle:Energia')->modificarEnergia($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Unidad de medida de Energía',
            'descripcion' => 'Se modificó la Unidad de medida de Energía:  '.$data['unidad']
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deleteEnergia", name="deleteEnergia")
     */
    public function deleteEnergiaAction()
    {
        $peticion = Request::createFromGlobals();
        $idEnergia = $peticion->request->get('idEnergia');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Energia')->eliminarEnergia($idEnergia);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Unidad de medida de Energía',
            'descripcion' => 'Se eliminó la Unidad de medida de Energía: '.$resp->getUnidad()
        ));
        return new Response('ok');
    }
}
