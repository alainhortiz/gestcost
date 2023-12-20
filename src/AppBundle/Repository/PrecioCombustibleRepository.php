<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PrecioCombustible;
use AppBundle\Entity\PrecioLubricante;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PrecioCombustibleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrecioCombustibleRepository extends EntityRepository
{

    public function agregarPrecioCombustible($data)
    {
        try {
            $em = $this->getEntityManager();

            $tipoCombustible = $em->getRepository('AppBundle:TipoCombustible')->find($data['idTipoCombustible']);

            foreach ($data['preciosMeses'] as $precio) {
                $precioCombustible = new PrecioCombustible();
                $precioCombustible->setYear($data['year']);
                $precioCombustible->setMes($precio['mes']);
                $precioCombustible->setPrecio($precio['precio']);
                $precioCombustible->setTipoCombustible($tipoCombustible);
                $em->persist($precioCombustible);
            }

            $em->flush();
            $msg = $precioCombustible;


        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0)
            {
                $msg = 'El precio de este tipo de combustible ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al asignar el precio de este tipo de combustible';
            }
        }
        return $msg;
    }

    public function modificarPrecioCombustible($data)
    {
        try
        {
            $em = $this->getEntityManager();

            $tipoCombustible = $em->getRepository('AppBundle:TipoCombustible')->find($data['idTipoCombustible']);
            $precioCombustible = $em->getRepository('AppBundle:PrecioCombustible')->findOneBy(array('tipoCombustible' => $tipoCombustible, 'year' => $data['year'], 'mes' => $data['mes']));

            if (!empty($precioCombustible)) {
                $precioCombustible->setYear($data['year']);
                $precioCombustible->setTipoCombustible($tipoCombustible);
                $precioCombustible->setMes($data['mes']);
                $precioCombustible->setPrecio($data['precio']);

                $em->flush();
                $msg = $precioCombustible;
            } else {
                $msg = $precioCombustible;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el precio de este tipo de combustible';
        }

        return $msg;
    }

    public function agregarPrecioAceite($data)
    {
        try {
            $em = $this->getEntityManager();

            $tipoAceite = $em->getRepository('AppBundle:Lubricante')->find($data['idTipoAceite']);

            foreach ($data['preciosMeses'] as $precio) {
                $precioAceite = new PrecioLubricante();
                $precioAceite->setYear($data['year']);
                $precioAceite->setMes($precio['mes']);
                $precioAceite->setPrecio($precio['precio']);
                $precioAceite->setLubricante($tipoAceite);
                $em->persist($precioAceite);
            }

            $em->flush();
            $msg = $precioAceite;


        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0)
            {
                $msg = 'El precio de este tipo de aceite ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al asignar el precio de este tipo de aceite';
            }
        }
        return $msg;
    }

    public function modificarPrecioAceite($data)
    {
        try
        {
            $em = $this->getEntityManager();

            $tipoAceite = $em->getRepository('AppBundle:Lubricante')->find($data['idTipoAceite']);
            $precioAceite = $em->getRepository('AppBundle:PrecioLubricante')->findOneBy(array('lubricante' => $tipoAceite, 'year' => $data['year'], 'mes' => $data['mes']));

            if (!empty($precioAceite)) {
                $precioAceite->setYear($data['year']);
                $precioAceite->setLubricante($tipoAceite);
                $precioAceite->setMes($data['mes']);
                $precioAceite->setPrecio($data['precio']);

                $em->flush();
                $msg = $precioAceite;
            } else {
                $msg = $precioAceite;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el precio de este tipo de aceite';
        }

        return $msg;
    }

    public function precioTipoCombustible($year,$idTipoCombustible)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT p.mes, p.precio
                FROM AppBundle:PrecioCombustible p
                JOIN p.tipoCombustible t
                WHERE p.year =:p1
                AND t.id =:p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);
        $query->setParameter('p2', $idTipoCombustible);

        return $query->getResult();

    }

    public function precioMesTipoCombustible($year,$idTipoCombustible,$mes)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT p.mes, p.precio
                FROM AppBundle:PrecioCombustible p
                JOIN p.tipoCombustible t
                WHERE p.mes =:p1
                AND p.year =:p2
                AND t.id =:p3';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $mes);
        $query->setParameter('p2', $year);
        $query->setParameter('p2', $idTipoCombustible);

        return $query->getResult();

    }

}
