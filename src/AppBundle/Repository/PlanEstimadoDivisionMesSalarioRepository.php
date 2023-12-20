<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoDivisionMesSalario;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoDivisionMesSalarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoDivisionMesSalarioRepository extends EntityRepository
{
    public function masterAgregarEstimadoFondoDivisionMes($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para las divisiones
            $estimadoDivisionMes = $this->agregarEstimadoFondoDivisionMes($data);

            if(is_string($estimadoDivisionMes)) {
                $em->rollback();
                $msg = $estimadoDivisionMes;
            } else {
                // aprobar la distribucion mensual para esta division
                $em->getRepository('AppBundle:PlanEstimadoDivisionSalario')->aprobarTotalEstimadoDivisionMesFondo($data);
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar fondo de salario mensual por división al Plan de Recursos humanos',
                    'descripcion' => 'Se asignó el total del fondo de salario mensual a: '.$estimadoDivisionMes->getDivisionCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el fondo de salario mensual por divisiones del Plan de Recursos Humanos';
        }
        return $msg;
    }

    public function totalEstimadoDivisionMesVenta($data,$mes)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.totalVentaDivisionMes 
                FROM AppBundle:PlanEstimadoDivisionMes e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE e.mes =:p1
                AND d.id =:p2
                AND p.id =:p3';

        $query = $em->createQuery($dql);
        $query->setParameter('p1',$mes);
        $query->setParameter('p2', $data['idDivisionCentroCosto']);
        $query->setParameter('p3', $data['idPlanEstimado']);

        return $query->getResult();

    }

    public function agregarEstimadoFondoDivisionMes($data)
    {
        try {
            $em = $this->getEntityManager();

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            foreach ($data['presupuesto'] as $registro) {
                //Obtener el total de ventas para esta division
                $totalVentaDivisionMes = $this->totalEstimadoDivisionMesVenta($data,$registro['mes']);

                if (empty($totalVentaDivisionMes)) {
                    $totalVentaDivisionMes = 0;
                }else{
                    $totalVentaDivisionMes = $totalVentaDivisionMes[0]['totalVentaDivisionMes'];
                }

                //Calcular el gasto de salario x peso de produccion de la division
                if ($totalVentaDivisionMes !== 0) {
                    $GSXPP = (int) ($registro['presupuesto'] / $totalVentaDivisionMes);
                } else {
                    $GSXPP = 0;
                }

                //Calcular el salario medio de la division
                if ($data['promedioTrabajador'] !== 0) {
                    $SM = (int) ($registro['presupuesto'] / $data['promedioTrabajador']);
                } else {
                    $SM = 0;
                }
                $totalEstimadoDivisionMes = new PlanEstimadoDivisionMesSalario();
                $totalEstimadoDivisionMes->setMes($registro['mes']);
                $totalEstimadoDivisionMes->setTotalGastoSalarioPesoProduccionDivisionMes($GSXPP);
                $totalEstimadoDivisionMes->setTotalSalarioDivisionMes($registro['presupuesto']);
                $totalEstimadoDivisionMes->setTotalPromedioTrabajadorMes($data['promedioTrabajador']);
                $totalEstimadoDivisionMes->setTotalSalarioMedioDivisionMes($SM);
                $totalEstimadoDivisionMes->setDivisionCentroCosto($divisionCentroCosto);
                $totalEstimadoDivisionMes->setPlanEstimadoIndicadores($planEstimado);
                $em->persist($totalEstimadoDivisionMes);
            }

            $em->flush();
            $msg = $totalEstimadoDivisionMes;


        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0)
            {
                $msg = 'El total del fondo de salario mensual para esta división ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al asignar el fondo de salario mensual para esta división';
            }
        }
        return $msg;
    }

    public function graficosFondoEstimadoDivisionMensual($idPlanEstimado,$division)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalSalarioDivisionMes,e.totalGastoSalarioPesoProduccionDivisionMes,e.totalPromedioTrabajadorMes,e.totalSalarioMedioDivisionMes 
                FROM AppBundle:PlanEstimadoDivisionMesSalario e 
                JOIN e.divisionCentroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE c.id = :p1 
                AND p.id = :p2
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $division);
        $query->setParameter('p2' , $idPlanEstimado);

        return $query->getResult();

    }

    public function graficosFondoEstimadoDivisionMensualTodos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT c.id as division, e.mes, e.totalSalarioDivisionMes, e.totalPromedioTrabajadorMes, e.totalGastoSalarioPesoProduccionDivisionMes, e.totalSalarioMedioDivisionMes 
                FROM AppBundle:PlanEstimadoDivisionMesSalario e 
                JOIN e.divisionCentroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    // Funciones para la exportarción a Excel el Plan
    public function datosExportEstimadoSalarioDivisionMes($idPlanEstimado,$idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, e.mes, e.totalSalarioDivisionMes, e.totalPromedioTrabajadorMes, e.totalGastoSalarioPesoProduccionDivisionMes, e.totalSalarioMedioDivisionMes
                FROM AppBundle:PlanEstimadoDivisionMesSalario e
                JOIN e.planEstimadoIndicadores p
                JOIN e.divisionCentroCosto d
                WHERE d.id =:p1 
                AND p.id =:p2
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadoFondoDivisionUnica($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT (d.nombre) as division
                FROM AppBundle:PlanEstimadoDivisionMesSalario e
                JOIN e.planEstimadoIndicadores p
                JOIN e.divisionCentroCosto d
                WHERE p.id = :p1
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadoFondoDivisionMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, e.mes, e.totalSalarioDivisionMes, e.totalGastoSalarioPesoProduccionDivisionMes, e.totalSalarioMedioDivisionMes, e.totalPromedioTrabajadorMes
                FROM AppBundle:PlanEstimadoDivisionMesSalario e
                JOIN e.planEstimadoIndicadores p
                JOIN e.divisionCentroCosto d
                WHERE p.id = :p1
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportTotalEstimadoDivisionMesVenta($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, e.mes, e.totalVentaDivisionMes
                FROM AppBundle:PlanEstimadoDivisionMes e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }
}
