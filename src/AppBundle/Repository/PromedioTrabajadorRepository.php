<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PromedioTrabajadorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PromedioTrabajadorRepository extends EntityRepository
{
    public function modificarPromedioTrabajador($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $promedioTrabajador = $em->getRepository('AppBundle:PromedioTrabajador')->find($data['idPromedioTrabajador']);

            if (!empty($promedioTrabajador)) {
                $promedioTrabajador->setTotal($data['total']);

                $em->flush();
                $msg = $promedioTrabajador;
            } else {
                $msg = $promedioTrabajador;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el total de promedio de trabajadores';
        }

        return $msg;
    }
}
