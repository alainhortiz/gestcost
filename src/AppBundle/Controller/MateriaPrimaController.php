<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MateriaPrimaController extends Controller
{
    /**
     * @Route("/gestionarMateriasPrimas", name="gestionarMateriasPrimas")
     */
    public function gestionarMateriasPrimasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $materiasPrimas  = $em->getRepository('AppBundle:MateriaPrima')->findAll();

         $this->forward('AppBundle:Traza:insertTraza' , array(
             'username' => $user->getUsername(),
             'nombre' => $user->getNombre(),
             'operacion' => 'Nomencladores - Gestionar Materias Primas',
             'descripcion' => 'Listado de Materias Primas'
    ));

        return $this->render('Nomencladores/GestionMateriaPrima/gestionMateriaPrima.twig' , array('materiasPrimas' => $materiasPrimas));
    }

    /**
     * @Route("/insertMateriaPrima", name="insertMateriaPrima")
     */
    public function insertMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );
        $resp = $em->getRepository('AppBundle:MateriaPrima')->agregarMateriaPrima($data);

        if(is_string($resp)) return new Response($resp);
        else{
            $this->forward('AppBundle:Traza:insertTraza' , array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Insertar Materia Prima',
                'descripcion' => 'Se insertó una nueva Materia Prima con el nombre: '.$data['nombre']
            ));
            return new Response('ok');
        }
    }

    /**
     * @Route("/updateMateriaPrima", name="updateMateriaPrima")
     */
    public function updateMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idMateriaPrima' => $peticion->request->get('idMateriaPrima'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'activo' => $peticion->request->get('activo'),
        );

        $resp = $em->getRepository('AppBundle:MateriaPrima')->modificarMateriaPrima($data);


        if(is_string($resp)) return new Response($resp);
        else{
            $this->forward('AppBundle:Traza:insertTraza' , array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Modificar Materia Prima',
                'descripcion' => 'Se modificó la Materia Prima:  '.$data['nombre']
            ));
            return new Response('ok');
        }
    }

    /**
     * @Route("/deleteMateriaPrima", name="deleteMateriaPrima")
     */
    public function deleteMateriaPrimaAction()
    {
        $peticion = Request::createFromGlobals();
        $idMateriaPrima = $peticion->request->get('idMateriaPrima');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:MateriaPrima')->eliminarMateriaPrima($idMateriaPrima);

        if(is_string($resp)) return new Response($resp);
        else{
            $this->forward('AppBundle:Traza:insertTraza' , array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Eliminar Materia Prima',
                'descripcion' => 'Se eliminó la Materia Prima: '.$resp->getNombre()
            ));
            return new Response('ok');
        }
    }
}
