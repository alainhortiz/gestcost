<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndicadorController extends Controller
{
    /**
     * @Route("/gestionarIndicadores", name="gestionarIndicadores")
     */
    public function gestionarIndicadoresAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $indicadores  = $em->getRepository('AppBundle:Indicador')->findBy(array(),array('id' => 'ASC'));

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Nomencladores - Gestionar Indicadores',
            'descripcion' => 'Listado de Indicadores'
        ));

        return $this->render('Nomencladores/GestionIndicadores/gestionIndicador.twig' , array(
            'indicadores' => $indicadores
        ));
    }

    /**
     * @Route("/updateIndicador", name="updateIndicador")
     */
    public function updateIndicadorAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idIndicador' => $peticion->request->get('idIndicador'),
            'codigo' => $peticion->request->get('codigo')
        );

        $resp = $em->getRepository('AppBundle:Indicador')->modificarIndicador($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        $this->forward('AppBundle:Traza:insertTraza' , array(
            'username' => $user->getUsername(),
            'nombre' => $user->getNombre(),
            'operacion' => 'Modificar Indicador',
            'descripcion' => 'Se modificó el Indicador:  '.$resp->getNombre()
        ));
        return new Response('ok');
    }

}
