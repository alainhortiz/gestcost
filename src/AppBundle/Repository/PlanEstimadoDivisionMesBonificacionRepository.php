<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoDivisionMesBonificacion;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoDivisionMesBonificacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoDivisionMesBonificacionRepository extends EntityRepository
{
    public function graficosBonificacionEstimadoDivisionMensual($idPlanEstimado,$division)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalBonificacion
                FROM AppBundle:PlanEstimadoDivisionMesBonificacion e 
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE d.id = :p1 
                AND p.id = :p2
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $division);
        $query->setParameter('p2' , $idPlanEstimado);

        return $query->getResult();

    }

    public function graficoTotalesEstimadosMesesBonificaciones($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,SUM(e.totalBonificacion) as presupuesto
                FROM AppBundle:PlanEstimadoDivisionMesBonificacion e 
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                GROUP BY e.mes
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    public function masterAgregarEstimadoBonificacionDivisionMes($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para las divisiones
            $estimadoDivisionMes = $this->agregarEstimadoBonificacionDivisionMes($data);

            if(is_string($estimadoDivisionMes)) {
                $em->rollback();
                $msg = $estimadoDivisionMes;
            } else {
                // aprobar la distribucion mensual para esta division
                $em->getRepository('AppBundle:PlanEstimadoDivisionBonificacion')->aprobarTotalEstimadoDivisionMesBonificacion($data);
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar el presupuesto mensual por división al Plan de Bonificación',
                    'descripcion' => 'Se asignó el presupuesto mensual a: '.$estimadoDivisionMes->getDivisionCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por divisiones del Plan de Bonificación';
        }
        return $msg;
    }

    public function agregarEstimadoBonificacionDivisionMes($data)
    {
        try {
            $em = $this->getEntityManager();

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            foreach ($data['presupuesto'] as $registro) {
                $totalEstimadoDivisionMes = new PlanEstimadoDivisionMesBonificacion();
                $totalEstimadoDivisionMes->setMes($registro['mes']);
                $totalEstimadoDivisionMes->setTotalBonificacion($registro['presupuesto']);
                $totalEstimadoDivisionMes->setDivisionCentroCosto($divisionCentroCosto);
                $totalEstimadoDivisionMes->setPlanEstimadoIndicadores($planEstimado);
                $em->persist($totalEstimadoDivisionMes);
            }

            $em->flush();
            $msg = $totalEstimadoDivisionMes;


        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0)
            {
                $msg = 'El presupuesto mensual para esta división ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al asignar el presupuesto mensual para esta división';
            }
        }
        return $msg;
    }

    public function graficosBonificacionEstimadoDivisionMensualTodos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, e.mes, e.totalBonificacion
                FROM AppBundle:PlanEstimadoDivisionMesBonificacion e 
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    //Funciones para el export
    public function datosExportEstimadoBonificacionDivisionMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, e.mes, e.totalBonificacion as presupuesto
                FROM AppBundle:PlanEstimadoDivisionMesBonificacion e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }
}