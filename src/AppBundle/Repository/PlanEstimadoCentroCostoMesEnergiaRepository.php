<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoCentroCostoMesEnergia;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoCentroCostoMesEnergiaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoCentroCostoMesEnergiaRepository extends EntityRepository
{
    public function graficosEnergiaEstimadoCentroCostoMensualTodos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, c.nombre as centro, e.mes,e.totalKWCentroCostoMes,e.totalEnergiaPresupuesto  
                FROM AppBundle:PlanEstimadoCentroCostoMesEnergia e 
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    public function masterAgregarEstimadoEnergiaCentroCosto($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para los Centros de Costos
            $estimadoCentroCosto = $this->agregarTotalEstimadoEnergiaCentroCosto($data);

            if(is_string($estimadoCentroCosto)) {
                $em->rollback();
                $msg = $estimadoCentroCosto;
            } else{
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar presupuesto mensual para un centro de costo',
                    'descripcion' => 'Se asignó el presupuesto mensual de energía  a: '.$estimadoCentroCosto->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al asignar el total de kW mensual por Centros de Costos del Plan de Energía';
        }
        return $msg;
    }

    public function masterModificadorEstimadoEnergiaCentroCosto($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //Modificar el presupuesto mensual para las divisiones
            $estimadoCentroCosto = $this->modificarTotalEstimadoEnergiaCentroCosto($data);

            if(is_string($estimadoCentroCosto)) {
                $em->rollback();
                $msg = $estimadoCentroCosto;
            } else{
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Modificar el total de kW mensual por centro de costo al Plan de Energía',
                    'descripcion' => 'Se modificó el total de kW mensual de energía  a: '.$estimadoCentroCosto->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al modificar el total de kW mensual por centro de costo del Plan de Energía';
        }
        return $msg;
    }

    public function agregarTotalEstimadoEnergiaCentroCosto($data)
    {
        try {
            $em = $this->getEntityManager();
            $totalEstimadoCentroCostoEnergia = new PlanEstimadoCentroCostoMesEnergia();
            $totalEstimadoCentroCostoEnergia->setMes($data['mes']);
            $totalEstimadoCentroCostoEnergia->setTotalEnergiaPresupuesto($data['presupuesto']);
            $totalEstimadoCentroCostoEnergia->setTotalKWCentroCostoMes($data['kW']);

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['division']);
            $totalEstimadoCentroCostoEnergia->setDivisionCentroCosto($divisionCentroCosto);

            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $totalEstimadoCentroCostoEnergia->setCentroCosto($centroCosto);

            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $totalEstimadoCentroCostoEnergia->setPlanEstimadoIndicadores($planEstimado);

            $em->persist($totalEstimadoCentroCostoEnergia);
            $em->flush();
            $msg = $totalEstimadoCentroCostoEnergia;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El total de kW mensual para este centro de costo ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el total de kW mensual de este centro de costo';
            }
        }
        return $msg;
    }

    public function modificarTotalEstimadoEnergiaCentroCosto($data)
    {
        try {
            $em = $this->getEntityManager();
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $mes = $data['mes'];
            $totalEstimadoCentroCostoEnergia = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesEnergia')->findOneBy(array('mes' => $mes,'planEstimadoIndicadores' => $planEstimado, 'centroCosto' => $centroCosto));

            if (!empty($totalEstimadoCentroCostoEnergia)) {

                $totalEstimadoCentroCostoEnergia->setTotalEnergiaPresupuesto($data['presupuesto']);
                $totalEstimadoCentroCostoEnergia->setTotalKWCentroCostoMes($data['kW']);

                $em->persist($totalEstimadoCentroCostoEnergia);
                $em->flush();
                $msg = $totalEstimadoCentroCostoEnergia;
            } else {
                $msg = $totalEstimadoCentroCostoEnergia;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el total de kW mensual del centro de costo';
        }

        return $msg;
    }

    public function aprobarTotalEstimadoCentroCostoMesEnergia($data)
    {
        try {
            $em = $this->getEntityManager();
            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['idCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $aprobarEstimadoCentroCostoMesFondo = $em->getRepository('AppBundle:PlanEstimadoCentroCostoSalario')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'centroCosto' => $centroCosto));

            if (!empty($aprobarEstimadoCentroCostoMesFondo)) {

                $aprobarEstimadoCentroCostoMesFondo->setAprobarPrespuestoCentroCostoMesFondo(true);

                $em->flush();
                $msg = $aprobarEstimadoCentroCostoMesFondo;
            } else {
                $msg = $aprobarEstimadoCentroCostoMesFondo;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al aprobar el fondo de salario mensual por centros de costos del Plan de Recursos humanos';
        }

        return $msg;
    }

    public function verificarAprobadoEstimadoEnergiaCentroCostoMeses($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.aprobarPrespuestoCentroCostoMesEnergia
                FROM AppBundle:PlanEstimadoDivisionEnergia e
                JOIN e.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobarPrespuestoCentroCostoMesEnergia = :p1 
                AND d.id = :p2
                AND p.id = :p3';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', false);
        $query->setParameter('p2', $data['division']);
        $query->setParameter('p3', $data['idPlanEstimado']);

        return count($query->getResult());

    }

    // Funciones para la exportarción a Excel el Plan

    public function graficosEnergiaEstimadoCentroCostoMensual($idPlanEstimado,$CentroCosto)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalKWCentroCostoMes,e.totalEnergiaPresupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMesEnergia e 
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE c.id = :p1 
                AND p.id = :p2
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $CentroCosto);
        $query->setParameter('p2' , $idPlanEstimado);

        return $query->getResult();

    }

    // Funciones para la exportarción a Excel el Plan
    public function datosExportEstimadoEnergiaCentroCostoMes($idPlanEstimado,$idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, e.totalKWCentroCostoMes,e.totalEnergiaPresupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMesEnergia e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE d.id = :p1 
                AND p.id = :p2
                GROUP BY c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadoEnergiaCentroCostoMeses($idPlanEstimado,$idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, e.mes, e.totalKWCentroCostoMes,e.totalEnergiaPresupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMesEnergia e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE d.id = :p1 
                AND p.id = :p2
                GROUP BY c.id, e.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

}
