<?php

namespace AppBundle\Repository;

use AppBundle\Entity\DivisionCentroCosto;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * DivisionCentroCostoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DivisionCentroCostoRepository extends EntityRepository
{
    public function agregarDivisionCentroCosto($data)
    {
        try{
            $em = $this->getEntityManager();
            $divisionCentroCosto = new DivisionCentroCosto();
            $divisionCentroCosto->setCodigo($data['codigo']);
            $divisionCentroCosto->setNombre($data['nombre']);

            $em->persist($divisionCentroCosto);
            $em->flush();
            $msg = $divisionCentroCosto;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'La división de Centro de Costo ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar la división del Centro de Costo';
            }
        }
        return $msg;
    }

    public function modificarDivisionCentroCosto($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);

            if (!empty($divisionCentroCosto)) {
                $divisionCentroCosto->setCodigo($data['codigo']);
                $divisionCentroCosto->setNombre($data['nombre']);
                $em->flush();
                $msg = $divisionCentroCosto;
            } else {
                $msg = $divisionCentroCosto;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar la división del Centro de Costo';
        }

        return $msg;
    }

    public function eliminarDivisionCentroCosto($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($id);

            if (!empty($divisionCentroCosto)) {
                $em->remove($divisionCentroCosto);
                $em->flush();
                $msg = $divisionCentroCosto;
            } else {
                $msg = $divisionCentroCosto;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), 'foreign key') > 0) {
                $msg = 'Existen Centros de Costos asociados a esta División, no se puede eliminar';
            } else {
                $msg = 'Se produjo un error al eliminar la División del Centro de Costo';
            }
        }
        return $msg;
    }
}