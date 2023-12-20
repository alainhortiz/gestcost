<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlantillaCargoController extends Controller
{
    /**
     * @Route("/gestionarPlantillaCargos", name="gestionarPlantillaCargos")
     */
    public function gestionarPlantillaCargosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tipoPlantillas  = $em->getRepository('AppBundle:TipoPlantillaCargo')->findAll();
        $denominadorCargos  = $em->getRepository('AppBundle:DenominadorCargo')->findAll();
        $divisionCentrosCostos  = $em->getRepository('AppBundle:DivisionCentroCosto')->findAll();
        $centrosCostos  = $em->getRepository('AppBundle:CentroCosto')->findAll();
        $plantillaCargos  = $em->getRepository('AppBundle:PlantillaCargo')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Plantilla de Cargos',
            'descripcion' => 'Listado de Plantilla de Cargos'
        ));

        return $this->render('Nomencladores/GestionPlantillaCargo/gestionPlantillaCargo.twig' , array(
            'tipoPlantillas' => $tipoPlantillas,
            'denominadorCargos' => $denominadorCargos,
            'divisionCentrosCostos' => $divisionCentrosCostos,
            'centrosCostos' => $centrosCostos,
            'plantillaCargos' => $plantillaCargos
        ));
    }

    /**
     * @Route("/insertPlantillaCargo", name="insertPlantillaCargo")
     */
    public function insertPlantillaCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'cantidad' => $peticion->request->get('cantidad'),
            'tipoPlantilla' => $peticion->request->get('tipoPlantilla'),
            'denominadorCargo' => $peticion->request->get('denominadorCargo'),
            'centroCosto' => $peticion->request->get('centroCosto')
        );

        $resp = $em->getRepository('AppBundle:PlantillaCargo')->agregarPlantillaCargo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Insertar Plantilla de Cargo',
            'descripcion' => 'Se insertó en la plantilla un nuevo cargo con el nombre: '.$resp->getDenominadorCargo()->getDenominadorCargo()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/updatePlantillaCargo", name="updatePlantillaCargo")
     */
    public function updatePlantillaCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPlantillaCargo' => $peticion->request->get('idPlantillaCargo'),
            'cantidad' => $peticion->request->get('cantidad'),
            'tipoPlantilla' => $peticion->request->get('tipoPlantilla'),
            'denominadorCargo' => $peticion->request->get('denominadorCargo'),
            'centroCosto' => $peticion->request->get('centroCosto')
        );

        $resp = $em->getRepository('AppBundle:PlantillaCargo')->modificarPlantillaCargo($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Plantilla de Cargo',
            'descripcion' => 'Se modificó en la plantilla el cargo:  '.$resp->getDenominadorCargo()->getDenominadorCargo()
        ));
        return new Response('ok');
    }

    /**
     * @Route("/deletePlantillaCargo", name="deletePlantillaCargo")
     */
    public function deletePlantillaCargoAction()
    {
        $peticion = Request::createFromGlobals();
        $idPlantillaCargo = $peticion->request->get('idPlantillaCargo');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:PlantillaCargo')->eliminarPlantillaCargo($idPlantillaCargo);

        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Eliminar Plantilla de Cargo',
            'descripcion' => 'Se eliminó de la plantilla el cargo: ' .$resp->getDenominadorCargo()->getDenominadorCargo()
        ));
        return new Response('ok');
    }
}
