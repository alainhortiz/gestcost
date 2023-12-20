<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LubricanteController extends Controller
{
    /**
     * @Route("/gestionarLubricantes", name="gestionarLubricantes")
     */
    public function gestionarLubricantesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $lubricantes  = $em->getRepository('AppBundle:Lubricante')->findAll();

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Aceites y Lubricantes',
            'descripcion' => 'Listado de Aceites y Lubricantes'
        ));

        return $this->render('Nomencladores/GestionLubricante/gestionLubricante.twig' , array('lubricantes' => $lubricantes));
    }

    /**
     * @Route("/updateLubricante", name="updateLubricante")
     */
    public function updateLubricanteAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idLubricante' => $peticion->request->get('idLubricante'),
            'precio' => $peticion->request->get('precio'),
        );

        $resp = $em->getRepository('AppBundle:Lubricante')->modificarLubricante($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar precio del Lubricante',
            'descripcion' => 'Se modificó el precio del Lubricante:  '.$data['precio']
        ));
        return new Response('ok');
    }

}
