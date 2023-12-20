<?php

namespace AppBundle\Repository;

use AppBundle\Entity\TipoTransporte;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * TipoTransporteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoTransporteRepository extends EntityRepository
{
    public function agregarTipoTransporte($data)
    {
        try{
            $em = $this->getEntityManager();
            $tipoTransporte = new TipoTransporte();
            $tipoTransporte->setNombre($data['nombre']);

            $em->persist($tipoTransporte);
            $em->flush();
            $msg = $tipoTransporte;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'El Tipo de Transporte ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar el Tipo de Transporte';
            }
        }
        return $msg;
    }

    public function modificarTipoTransporte($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $tipoTransporte = $em->getRepository('AppBundle:TipoTransporte')->find($data['idTipoTransporte']);

            if (!empty($tipoTransporte)) {
                $tipoTransporte->setNombre($data['nombre']);

                $em->persist($tipoTransporte);
                $em->flush();
                $msg = $tipoTransporte;
            } else {
                $msg = $tipoTransporte;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el Tipo de Transporte';
        }

        return $msg;
    }

    public function eliminarTipoTransporte($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $tipoTransporte = $em->getRepository('AppBundle:TipoTransporte')->find($id);

            if (!empty($tipoTransporte)) {
                $em->remove($tipoTransporte);
                $em->flush();
                $msg = $tipoTransporte;
            } else {
                $msg = $tipoTransporte;
            }

        }catch (Exception $e){

            if(strpos($e->getMessage() , 'foreign key') > 0)
            {
                $msg = 'Existen datos asociados a este tipo de transporte, no se puede eliminar';
            }
            else
            {
                $msg = 'Se produjo un error al eliminar el Tipo de Transporte';
            }

        }
        return $msg;
    }
}
