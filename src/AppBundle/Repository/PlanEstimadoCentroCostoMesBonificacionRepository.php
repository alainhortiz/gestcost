<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoCentroCostoMesBonificacion;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoCentroCostoMesBonificacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoCentroCostoMesBonificacionRepository extends EntityRepository
{
    public function graficosBonificacionEstimadoCentroCostoMensualTodos($idPlanEstimado)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT d.id as division, c.nombre as centro, e.mes,e.totalBonificacion
                FROM AppBundle:PlanEstimadoCentroCostoMesBonificacion e 
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE p.id = :p1
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1' , $idPlanEstimado);

        return $query->getResult();

    }

    public function graficoTotalesEstimadosCentroCostosBonificaciones($idPlanEstimado,$idDivision)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre, SUM(e.totalBonificacion) as totalBonificacion
                FROM AppBundle:PlanEstimadoCentroCostoMesBonificacion e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id =:p2
                GROUP BY c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivision);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function graficosBonificacionEstimadoCentroCostoMensual($idPlanEstimado,$CentroCosto)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalBonificacion
                FROM AppBundle:PlanEstimadoCentroCostoMesBonificacion e 
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

    public function masterAgregarEstimadoBonificacionCentroCosto($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para los Centros de Costos
            $estimadoCentroCosto = $this->agregarTotalEstimadoBonificacionCentroCosto($data);

            if(is_string($estimadoCentroCosto)) {
                $em->rollback();
                $msg = $estimadoCentroCosto;
            } else{
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar presupuesto mensual para un centro de costo',
                    'descripcion' => 'Se asignó el presupuesto mensual de bonificación  a: '.$estimadoCentroCosto->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al asignar el presupuesto mensual por Centros de Costos del Plan de Bonificación';
        }
        return $msg;
    }

    public function masterModificadorEstimadoBonificacionCentroCosto($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //Modificar el presupuesto mensual para las divisiones
            $estimadoCentroCosto = $this->modificarTotalEstimadoBonificacionCentroCosto($data);

            if(is_string($estimadoCentroCosto)) {
                $em->rollback();
                $msg = $estimadoCentroCosto;
            } else{
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Modificar el presupuesto mensual por centro de costo al Plan de Bonificación',
                    'descripcion' => 'Se modificó el presupuesto mensual de bonificación  a: '.$estimadoCentroCosto->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al modificar el presupuesto mensual por centro de costo del Plan de Bonificación';
        }
        return $msg;
    }

    public function agregarTotalEstimadoBonificacionCentroCosto($data)
    {
        try {
            $em = $this->getEntityManager();
            $totalEstimadoCentroCostoBonificacion = new PlanEstimadoCentroCostoMesBonificacion();
            $totalEstimadoCentroCostoBonificacion->setMes($data['mes']);
            $totalEstimadoCentroCostoBonificacion->setTotalBonificacion($data['presupuesto']);

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['division']);
            $totalEstimadoCentroCostoBonificacion->setDivisionCentroCosto($divisionCentroCosto);

            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $totalEstimadoCentroCostoBonificacion->setCentroCosto($centroCosto);

            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $totalEstimadoCentroCostoBonificacion->setPlanEstimadoIndicadores($planEstimado);

            $em->persist($totalEstimadoCentroCostoBonificacion);
            $em->flush();
            $msg = $totalEstimadoCentroCostoBonificacion;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El presupuesto mensual para este centro de costo ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el presupuesto mensual de este centro de costo';
            }
        }
        return $msg;
    }

    public function modificarTotalEstimadoBonificacionCentroCosto($data)
    {
        try {
            $em = $this->getEntityManager();
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $mes = $data['mes'];
            $totalEstimadoCentroCostoBonificacion = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMesBonificacion')->findOneBy(array('mes' => $mes,'planEstimadoIndicadores' => $planEstimado, 'centroCosto' => $centroCosto));

            if (!empty($totalEstimadoCentroCostoBonificacion)) {

                $totalEstimadoCentroCostoBonificacion->setTotalBonificacion($data['presupuesto']);

                $em->persist($totalEstimadoCentroCostoBonificacion);
                $em->flush();
                $msg = $totalEstimadoCentroCostoBonificacion;
            } else {
                $msg = $totalEstimadoCentroCostoBonificacion;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el total de presupuesto mensual del centro de costo';
        }

        return $msg;
    }

    public function verificarAprobadoEstimadoBonificacionCentroCostoMeses($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.aprobarPrespuestoCentroCostoMesBonificacion
                FROM AppBundle:PlanEstimadoDivisionBonificacion e
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobarPrespuestoCentroCostoMesBonificacion = :p1 
                AND p.id = :p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);
        $query->setParameter('p2', $data['idPlanEstimado']);

        return count($query->getResult());

    }

    //Funciones para el export
    public function datosExportEstimadoBonificacionCentroCostoMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, c.nombre as centro, e.mes, e.totalBonificacion as presupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMesBonificacion e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.id = :p1
                ORDER BY d.nombre, c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadosCentroCostosBonificaciones($idPlanEstimado,$idDivision)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, e.mes, e.totalBonificacion
                FROM AppBundle:PlanEstimadoCentroCostoMesBonificacion e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id =:p2
                GROUP BY c.id, e.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivision);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }
}