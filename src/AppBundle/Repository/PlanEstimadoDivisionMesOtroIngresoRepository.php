<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoDivisionMesOtroIngreso;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoDivisionMesOtroIngresoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoDivisionMesOtroIngresoRepository extends EntityRepository
{
    public function graficosOtroIngresoEstimadoDivisionMensual($idPlanEstimado,$division)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalOtroIngreso
                FROM AppBundle:PlanEstimadoDivisionMesOtroIngreso e 
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

    public function masterAgregarEstimadoOtroIngresoDivisionMes($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para las divisiones
            $estimadoDivisionMes = $this->agregarEstimadoOtroIngresoDivisionMes($data);

            if(is_string($estimadoDivisionMes)) {
                $em->rollback();
                $msg = $estimadoDivisionMes;
            } else {
                // aprobar la distribucion mensual para esta division
                $em->getRepository('AppBundle:PlanEstimadoDivisionOtroIngreso')->aprobarTotalEstimadoDivisionMesOtroIngreso($data);
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar el presupuesto mensual por división al Plan de Otros Ingresos',
                    'descripcion' => 'Se asignó el presupuesto mensual a: '.$estimadoDivisionMes->getDivisionCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por divisiones del Plan de Otros Ingresos';
        }
        return $msg;
    }

    public function agregarEstimadoOtroIngresoDivisionMes($data)
    {
        try {
            $em = $this->getEntityManager();

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            foreach ($data['presupuesto'] as $registro) {
                $totalEstimadoDivisionMes = new PlanEstimadoDivisionMesOtroIngreso();
                $totalEstimadoDivisionMes->setMes($registro['mes']);
                $totalEstimadoDivisionMes->setTotalOtroIngreso($registro['presupuesto']);
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

    public function graficosOtroIngresoEstimadoDivisionMensualTodos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, e.mes, e.totalOtroIngreso
                FROM AppBundle:PlanEstimadoDivisionMesOtroIngreso e 
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    public function graficoTotalesEstimadosMesesOtrosIngresos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,SUM(e.totalOtroIngreso) as presupuesto
                FROM AppBundle:PlanEstimadoDivisionMesOtroIngreso e 
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                GROUP BY e.mes
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    //Funciones para el export
    public function datosExportEstimadoOtroIngresoDivisionMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, e.mes, e.totalOtroIngreso as presupuesto
                FROM AppBundle:PlanEstimadoDivisionMesOtroIngreso e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

}