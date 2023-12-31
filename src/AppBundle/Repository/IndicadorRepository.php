<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * IndicadorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IndicadorRepository extends EntityRepository
{
    public function modificarIndicador($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $indicador = $em->getRepository('AppBundle:Indicador')->find($data['idIndicador']);

            if (!empty($indicador)) {
                $indicador->setCodigo($data['codigo']);
                $em->flush();
                $msg = $indicador;
            } else {
                $msg = $indicador;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el Indicador';
        }

        return $msg;
    }

    // Datos para exportar a Excel
    public function datosExportIndicadores()
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT t.nombre as asociado, i.codigo, i.nombre as indicador
                FROM AppBundle:Indicador i
                JOIN i.tipoIndicador t';

        return $em->createQuery($dql)->getResult();

    }

}
