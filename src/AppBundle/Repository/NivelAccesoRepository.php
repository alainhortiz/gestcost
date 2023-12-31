<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * NivelAccesoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NivelAccesoRepository extends EntityRepository
{
    public function listarNivelesAcceso($user)
    {
        $em = $this->getEntityManager();
        $nivelAccesoID = $user->getNivelAcceso()->getId();
        $dql = 'SELECT n FROM AppBundle:NivelAcceso n WHERE n.id <= :nivel';
        $query = $em->createQuery($dql);
        $query->setParameter('nivel' , $nivelAccesoID);
        return $query->getResult();
    }
}
