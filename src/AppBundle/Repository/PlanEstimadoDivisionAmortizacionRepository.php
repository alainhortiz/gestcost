<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoDivisionAmortizacion;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoDivisionAmortizacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoDivisionAmortizacionRepository extends EntityRepository
{
    public function totalEstimadoDivisionAmortizacion($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT sum(e.totalAmortizacion) as totalAmortizacion 
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        $total = $query->getResult();

        return $total[0]['totalAmortizacion'];

    }

    public function cantidadEstimadoDivisionAmortizacion($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT count(e.divisionCentroCosto) as cantidadAmortizacionDivision 
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        $total = $query->getResult();

        return $total[0]['cantidadAmortizacionDivision'];

    }

    public function cantidadEstimadoDivisionMesAmortizacion($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT count(e.divisionCentroCosto) as cantidadAmortizacionDivisionMes 
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobarPrespuestoDivisionMesAmortizacion =:p1
                AND p.id =:p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);
        $query->setParameter('p2', $idPlanEstimado);

        $total = $query->getResult();

        return $total[0]['cantidadAmortizacionDivisionMes'];

    }

    public function graficoTotalesEstimadosDivisionesAmortizacion($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, d.nombre, e.totalAmortizacion
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1
                ORDER BY e.totalAmortizacion DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function agregarTotalEstimadoAmortizacionDivision($data)
    {
        try {
            $em = $this->getEntityManager();
            $totalEstimadoDivisionAmortizacion = new PlanEstimadoDivisionAmortizacion();
            $totalEstimadoDivisionAmortizacion->setTotalAmortizacion($data['presupuesto']);

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['division']);
            $totalEstimadoDivisionAmortizacion->setDivisionCentroCosto($divisionCentroCosto);

            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $totalEstimadoDivisionAmortizacion->setPlanEstimadoIndicadores($planEstimado);

            $em->persist($totalEstimadoDivisionAmortizacion);
            $em->flush();
            $msg = $totalEstimadoDivisionAmortizacion;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El presupuesto de amortización para esta división ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el presupuesto de amortización para esta división';
            }
        }
        return $msg;
    }

    public function modificarTotalEstimadoAmortizacionDivision($data)
    {
        try {
            $em = $this->getEntityManager();
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['division']);

            $totalEstimadoDivisionAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'divisionCentroCosto' => $divisionCentroCosto));

            if (!empty($totalEstimadoDivisionAmortizacion)) {

                $totalEstimadoDivisionAmortizacion->setTotalAmortizacion($data['presupuesto']);

                $em->persist($totalEstimadoDivisionAmortizacion);
                $em->flush();
                $msg = $totalEstimadoDivisionAmortizacion;
            } else {
                $msg = $totalEstimadoDivisionAmortizacion;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el presupuesto de amortización para esta división';
        }

        return $msg;
    }

    public function aprobarTotalEstimadoDivisionMesAmortizacion($data)
    {
        try {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $aprobarEstimadoDivisionMesAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'divisionCentroCosto' => $divisionCentroCosto));

            if (!empty($aprobarEstimadoDivisionMesAmortizacion)) {

                $aprobarEstimadoDivisionMesAmortizacion->setAprobarPrespuestoDivisionMesAmortizacion(true);

                $em->flush();
                $msg = $aprobarEstimadoDivisionMesAmortizacion;
            } else {
                $msg = $aprobarEstimadoDivisionMesAmortizacion;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por divisiones del Plan de Amortización';
        }

        return $msg;
    }

    public function verificarAprobadoEstimadoAmortizacionDivisionMeses($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.aprobarPrespuestoDivisionMesAmortizacion
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobarPrespuestoDivisionMesAmortizacion = :p1 
                AND p.id = :p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', false);
        $query->setParameter('p2', $data['idPlanEstimado']);

        return count($query->getResult());

    }

    public function planAmortizacionDivisionUnica($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT d.id, d.nombre 
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function planAmortizacionCantidadDivisiones($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.id 
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $data['idPlanEstimado']);

        return count($query->getResult());

    }

    public function verificarAprobadoEstimadoAmortizacionCentroCostoMes($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, e.aprobarPrespuestoCentroCostoMesAmortizacion as aprobado
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobarPrespuestoCentroCostoMesAmortizacion = :p1 
                AND p.id = :p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function aprobarTotalEstimadoCentroCostoAmortizacion($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['division']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $aprobarEstimadoCentroCostoAmortizacion = $em->getRepository('AppBundle:PlanEstimadoDivisionAmortizacion')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'divisionCentroCosto' => $divisionCentroCosto));

            if (!empty($aprobarEstimadoCentroCostoAmortizacion)) {

                $aprobarEstimadoCentroCostoAmortizacion->setAprobarPrespuestoCentroCostoMesAmortizacion(true);

                $em->flush();
                $msg = $aprobarEstimadoCentroCostoAmortizacion;
            } else {
                $msg = $aprobarEstimadoCentroCostoAmortizacion;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al aprobar el presupuesto mensual para los centros de costos de esta división';
        }

        return $msg;
    }

    public function planAmortizacionDivisionUnicaExportar($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT (d.nombre) as division
                FROM AppBundle:PlanEstimadoDivisionAmortizacion e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

}
