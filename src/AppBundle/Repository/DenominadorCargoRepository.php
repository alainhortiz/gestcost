<?php

namespace AppBundle\Repository;

use AppBundle\Entity\DenominadorCargo;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * DenominadorCargoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DenominadorCargoRepository extends EntityRepository
{
    public function agregarDenominadorCargo($data)
    {
        try{
            $em = $this->getEntityManager();
            $denominadorCargo = new DenominadorCargo();
            $denominadorCargo->setCodigo($data['codigo']);
            $denominadorCargo->setDenominadorCargo($data['denominadorCargo']);
            $denominadorCargo->setGrupoEscala($data['grupoEscala']);
            $denominadorCargo->setCategoria($data['categoria']);
            $denominadorCargo->setSalario($data['salario']);

            $em->persist($denominadorCargo);
            $em->flush();
            $msg = $denominadorCargo;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'El denominador de cargo ya existe ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar el denominador de cargo';
            }
        }
        return $msg;
    }

    public function modificarDenominadorCargo($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $denominadorCargo = $em->getRepository('AppBundle:DenominadorCargo')->find($data['idDenominadorCargo']);

            if (!empty($denominadorCargo)) {
                $denominadorCargo->setCodigo($data['codigo']);
                $denominadorCargo->setDenominadorCargo($data['denominadorCargo']);
                $denominadorCargo->setGrupoEscala($data['grupoEscala']);
                $denominadorCargo->setCategoria($data['categoria']);
                $denominadorCargo->setSalario($data['salario']);
                $em->flush();
                $msg = $denominadorCargo;
            } else {
                $msg = $denominadorCargo;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el denominador de cargo';
        }

        return $msg;
    }

    public function eliminarDenominadorCargo($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $denominadorCargo = $em->getRepository('AppBundle:DenominadorCargo')->find($id);

            if (!empty($denominadorCargo)) {
                $em->remove($denominadorCargo);
                $em->flush();
                $msg = $denominadorCargo;
            } else {
                $msg = $denominadorCargo;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), 'foreign key') > 0) {
                $msg = 'Existen cargos asociados a este denominador de cargo, no se puede eliminar';
            } else {
                $msg = 'Se produjo un error al eliminar el denominador de cargo';
            }
        }
        return $msg;
    }

    // Datos para exportar a Excel
    public function datosExportDenominadorCargos()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.denominadorCargo as denominador, e.grupoEscala as escala, e.categoria 
                FROM AppBundle:Denominadorcargo e';

        return $em->createQuery($dql)->getResult();

    }
}
