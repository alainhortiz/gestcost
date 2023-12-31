<?php

namespace AppBundle\Repository;

use AppBundle\Entity\TipoServicio;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * TipoServicioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoServicioRepository extends EntityRepository
{
    public function agregarTipoServicio($data)
    {
        try{
            $em = $this->getEntityManager();
            $tipoServicio = new TipoServicio();
            $tipoServicio->setNombre($data['nombre']);

            $em->persist($tipoServicio);
            $em->flush();
            $msg = $tipoServicio;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'El tipo de servicio ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar el tipo de servicio';
            }
        }
        return $msg;
    }

    public function modificarTipoServicio($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $tipoServicio = $em->getRepository('AppBundle:TipoServicio')->find($data['idTipoServicio']);

            if (!empty($tipoServicio)) {
                $tipoServicio->setNombre($data['nombre']);
                $em->flush();
                $msg = $tipoServicio;
            } else {
                $msg = $tipoServicio;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el tipo de servicio';
        }

        return $msg;
    }

    public function eliminarTipoServicio($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $tipoServicio = $em->getRepository('AppBundle:TipoServicio')->find($id);

            if (!empty($tipoServicio)) {
                $em->remove($tipoServicio);
                $em->flush();
                $msg = $tipoServicio;
            } else {
                $msg = $tipoServicio;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), 'foreign key') > 0) {
                $msg = 'Existen servicios asociados a este tipo de servicio, no se puede eliminar';
            } else {
                $msg = 'Se produjo un error al eliminar el tipo de servicio';
            }
        }
        return $msg;
    }

}
