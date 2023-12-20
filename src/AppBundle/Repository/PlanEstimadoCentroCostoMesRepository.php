<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoCentroCostoMes;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoCentroCostoMesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoCentroCostoMesRepository extends EntityRepository
{

    public function totalEstimadoCentroCostoVenta($idPlanEstimado,$idCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT sum(e.totalVentaCentroCostoMes) as totalVentaCentroCosto
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id =:p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        $total = $query->getResult();

        return $total[0]['totalVentaCentroCosto'];

    }

    public function graficoTotalesEstimadosCentroCostosVentas($idPlanEstimado,$idCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre, SUM(e.totalVentaCentroCostoMes) as totalVentaCentroCosto
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id =:p2
                GROUP BY c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function graficoTotalesEstimadosMensualCentroCostosVentas($idPlanEstimado,$idCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes, e.totalVentaCentroCostoMes 
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE c.id =:p1
                AND p.id =:p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function masterAgregarEstimadoVentaCentroCostoMes($data, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            //agregar el presupuesto mensual para los centros de costos
            $estimadoCentroCostoMes = $this->agregarEstimadoVentaCentroCostoMes($data);

            // aprobar la distribucion mensual para este centro de costo
           // $em->getRepository('AppBundle:PlanEstimadoCentroCosto')->aprobarTotalEstimadoCentroCostoMesVenta($data);

            if(is_string($estimadoCentroCostoMes)) {
                $em->rollback();
                $msg = $estimadoCentroCostoMes;
            } else {
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar presupuesto mensual por centro de costo al Plan de Venta',
                    'descripcion' => 'Se asignó el total del presupuesto mensual a: ' . $estimadoCentroCostoMes->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por centros de costos del Plan de Ventas';
        }
        return $msg;
    }

    public function agregarEstimadoVentaCentroCostoMes($data)
    {
        try {
            $em = $this->getEntityManager();

            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $totalEstimadoCentroCostoMes = new PlanEstimadoCentroCostoMes();
            $totalEstimadoCentroCostoMes->setMes($data['mes']);
            $totalEstimadoCentroCostoMes->setTotalVentaCentroCostoMes($data['presupuesto']);
            $totalEstimadoCentroCostoMes->setDivisionCentroCosto($divisionCentroCosto);
            $totalEstimadoCentroCostoMes->setCentroCosto($centroCosto);
            $totalEstimadoCentroCostoMes->setPlanEstimadoIndicadores($planEstimado);
            $em->persist($totalEstimadoCentroCostoMes);

            $em->flush();
            $msg = $totalEstimadoCentroCostoMes;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El total del presupuesto mensual para este centro de costo ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el presupuesto mensual para este centro de costo';
            }
        }
        return $msg;
    }

    public function masterModificarEstimadoVentaCentroCostoMes($data, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            //agregar el presupuesto mensual para los centros de costos
            $estimadoCentroCostoMes = $this->modificarEstimadoVentaCentroCostoMes($data);

            // aprobar la distribucion mensual para este centro de costo
            // $em->getRepository('AppBundle:PlanEstimadoCentroCosto')->aprobarTotalEstimadoCentroCostoMesVenta($data);

            if(is_string($estimadoCentroCostoMes)) {
                $em->rollback();
                $msg = $estimadoCentroCostoMes;
            } else {
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Modificar el presupuesto mensual por centro de costo al Plan de Venta',
                    'descripcion' => 'Se modificó el total del presupuesto mensual a: ' . $estimadoCentroCostoMes->getCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por centros de costos del Plan de Ventas';
        }
        return $msg;
    }

    public function modificarEstimadoVentaCentroCostoMes($data)
    {
        try {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $totalEstimadoCentroCostoMes = $em->getRepository('AppBundle:PlanEstimadoCentroCostoMes')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'centroCosto' => $centroCosto, 'mes' => $data['mes']));

            if (!empty($totalEstimadoCentroCostoMes)) {
                $totalEstimadoCentroCostoMes->setMes($data['mes']);
                $totalEstimadoCentroCostoMes->setTotalVentaCentroCostoMes($data['presupuesto']);
                $totalEstimadoCentroCostoMes->setDivisionCentroCosto($divisionCentroCosto);
                $totalEstimadoCentroCostoMes->setCentroCosto($centroCosto);
                $totalEstimadoCentroCostoMes->setPlanEstimadoIndicadores($planEstimado);

                $em->persist($totalEstimadoCentroCostoMes);
                $em->flush();
                $msg = $totalEstimadoCentroCostoMes;
            } else {
                $msg = $totalEstimadoCentroCostoMes;
            }

        } catch (Exception $e) {
                $msg = 'Se produjo un error al asignar el presupuesto mensual para este centro de costo';
        }
        return $msg;
    }

    public function masterAprobarEstimadoVentaCentroCostoMes($data, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            //agregar el presupuesto mensual para los centros de costos
            $estimadoCentroCostoMes = $this->aprobarEstimadoVentaCentroCostoMes($data);

            if(is_string($estimadoCentroCostoMes)) {
                $em->rollback();
                $msg = $estimadoCentroCostoMes;
            } else {
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar plan mensual por centro de costo al Plan de Venta',
                    'descripcion' => 'Se asignó el plan mensual a: ' . $estimadoCentroCostoMes->getDivisionCentroCosto()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al aprobar el presupuesto mensual por centros de costos del Plan de Ventas';
        }
        return $msg;
    }

    public function aprobarEstimadoVentaCentroCostoMes($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($data['idDivisionCentroCosto']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $aprobarEstimadoCentroCostoVenta = $em->getRepository('AppBundle:PlanEstimadoDivision')->findOneBy(array('planEstimadoIndicadores' => $planEstimado, 'divisionCentroCosto' => $divisionCentroCosto));

            if (!empty($aprobarEstimadoCentroCostoVenta)) {

                $aprobarEstimadoCentroCostoVenta->setAprobadoPlanVentasMensualCentroCosto(true);

                $em->flush();
                $msg = $aprobarEstimadoCentroCostoVenta;
            } else {
                $msg = $aprobarEstimadoCentroCostoVenta;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al aprobar el plan mensual de ventas por centros de costos de esta división.';
        }

        return $msg;
    }

    public function graficosVentaEstimadoCentroCostoMensual($idPlanEstimado, $CentroCosto)
    {

        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.totalVentaCentroCostoMes 
                FROM AppBundle:PlanEstimadoCentroCostoMes e 
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE c.id = :p1 
                AND p.id = :p2
                ORDER BY e.id ASC';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $CentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function estimadoVentaCentroCostoMensualUnico($idPlanEstimado, $idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes, c.nombre as centro, e.totalVentaCentroCostoMes as presupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.planEstimadoIndicadores p
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                WHERE d.id = :p1 
                AND p.id = :p2
                ORDER BY e.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function progresoVentaEstimadoCentroCostoMensual($idPlanEstimado, $idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes, SUM(e.totalVentaCentroCostoMes) as presupuestoDistribuido
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.planEstimadoIndicadores p
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                WHERE d.id = :p1 
                AND p.id = :p2
                GROUP BY e.mes
                ORDER BY e.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function verificarAprobadoEstimadoVentaCentroCostoMeses($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.aprobadoPlanVentasMensualCentroCosto
                FROM AppBundle:PlanEstimadoDivision e
                JOIN e.planEstimadoIndicadores p
                WHERE e.aprobadoPlanVentasMensualCentroCosto = :p1 
                AND p.id = :p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', false);
        $query->setParameter('p2', $data['idPlanEstimado']);

        return count($query->getResult());

    }

    //Funciones para el Plan de Materia Primas
    public function planVentaCentroCostoUnico($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT  d.id as divisionId, c.id, c.nombre 
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.id = :p1
                ORDER BY d.id, c.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function planVentaCentroCostoCoeficienteTotal($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.id as centro, SUM(e.totalVentaCentroCostoMes) as total 
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id = :p2
                GROUP BY c.id
                ORDER BY c.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $data['division']);
        $query->setParameter('p2', $data['idPlanEstimado']);

        return $query->getResult();

    }

    public function planVentaCentroCostoCoeficienteMensual($data)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.id as centro, e.mes, e.totalVentaCentroCostoMes as total 
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.divisionCentroCosto d
                JOIN e.centroCosto c
                JOIN e.planEstimadoIndicadores p
                WHERE d.id =:p1
                AND p.id = :p2
                ORDER BY c.id, e.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $data['division']);
        $query->setParameter('p2', $data['idPlanEstimado']);

        return $query->getResult();

    }

    // Funciones para la exportarción a Excel el Plan
    public function datosExportEstimadoVentaCentroCostoMes($idPlanEstimado, $idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, e.mes, e.totalVentaCentroCostoMes
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE d.id = :p1 
                AND p.id = :p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idDivisionCentroCosto);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadoVentaCentroCostoMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, c.nombre as centro, e.mes, e.totalVentaCentroCostoMes as presupuesto
                FROM AppBundle:PlanEstimadoCentroCostoMes e
                JOIN e.planEstimadoIndicadores p
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.id = :p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function datosExportEstimadoVentaCentroCosto($idPlanEstimado,$idDivisionCentroCosto)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, SUM(e.totalVentaCentroCostoMes) as totalVentaCentroCosto
                FROM AppBundle:PlanEstimadoCentroCostoMes e
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

}
