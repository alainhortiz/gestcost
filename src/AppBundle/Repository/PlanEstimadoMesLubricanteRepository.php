<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoMesLubricante;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoMesLubricanteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoMesLubricanteRepository extends EntityRepository
{
    public function presupuestoTipoLubricanteMedio($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.id, t.id as idTransporte,m.id as modeloId, r.id as idTipoTransporte, m.nombre as modeloNombre, c.id as tipoLubricanteId, c.nombre as tipoLubricanteNombre ,SUM(e.ltsLubricante) as ltsLubricante, SUM(e.importeLubricante) as importeLubricante, t.matricula, o.nombre as centro, d.nombre as division, t.isLubricante
                FROM AppBundle:PlanEstimadoMesLubricante e
                JOIN e.transporte t
                JOIN t.modeloTransporte m
                JOIN m.tipoTransporte r
                JOIN t.lubricante c
                JOIN t.centroCosto o
                JOIN o.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1
                GROUP BY t.id';


        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function presupuestoTipoLubricanteMedioMes($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.id, e.mes, t.id as idTransporte,m.id as modeloId, r.id as idTipoLubricante, m.nombre as modeloNombre, c.id as tipoLubricanteId, c.nombre as tipoLubricanteNombre ,e.ltsLubricante, e.importeLubricante, t.matricula, o.nombre as centro, d.nombre as division, t.isLubricante
                FROM AppBundle:PlanEstimadoMesLubricante e
                JOIN e.transporte t
                JOIN t.modeloTransporte m
                JOIN m.tipoTransporte r
                JOIN t.lubricante c
                JOIN t.centroCosto o
                JOIN o.divisionCentroCosto d
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();

    }

    public function presupuestoTipoLubricanteMedioCantMeses($idPlanEstimado)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT t.id as idTransporte,count(e) as cantidad
                FROM AppBundle:PlanEstimadoMesLubricante e
                JOIN e.transporte t
                JOIN e.planEstimadoIndicadores p
                WHERE p.id =:p1
                GROUP BY t.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $idPlanEstimado);

        return $query->getResult();
    }

    public function presupuestoTipoLubricanteMedioMeses($idPlanEstimado, $transporte)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT e.mes,e.precioLubricante
                FROM AppBundle:PlanEstimadoMesLubricante e
                JOIN e.transporte t
                JOIN e.planEstimadoIndicadores p
                WHERE t.id =:p1
                AND p.id =:p2';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $transporte);
        $query->setParameter('p2', $idPlanEstimado);

        return $query->getResult();
    }

    public function masterAgregarTotalLubricanteMedioTransporteMes($data, $meses, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {

            if ($data['mes'] === '') {
                //agregar el
                $agregarLubricante = $this->agregarEstimadoLubricanteMes($data, $meses);
            } else {
                //agregar el
                $agregarLubricante = $this->agregarEstimadoLubricanteUnMes($data);
            }

            if (is_string($agregarLubricante)) {
                $em->rollback();
                return $agregarLubricante;
            }

            //se crea la traza
            $dataTraza = array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Asignación mensual del total de litros de aceite',
                'descripcion' => 'Se asignó el total mensual de litros de aceite para el transporte: '
            );
            $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);


            $em->commit();
            $msg = 'ok';


        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al asignar el total de litros de aeceite estimados a este medio de transporte';
        }
        return $msg;
    }

    public function agregarEstimadoLubricanteMes($data, $meses)
    {
        try {
            $em = $this->getEntityManager();

            $trasporte = $em->getRepository('AppBundle:Transporte')->find($data['transporte']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);


            foreach ($meses as $mes) {
                $totalEstimadoLubricante = new PlanEstimadoMesLubricante();
                $totalEstimadoLubricante->setMes($mes['mes']);
                $totalEstimadoLubricante->setLtsLubricante($data['ltsLubricante']);
                $totalEstimadoLubricante->setPrecioLubricante((float) $mes['precio']);

                $importeLubricante = (float)($data['ltsLubricante'] * $mes['precio']);

                $totalEstimadoLubricante->setImporteLubricante($importeLubricante);
                $totalEstimadoLubricante->setTransporte($trasporte);
                $totalEstimadoLubricante->setPlanEstimadoIndicadores($planEstimado);
                $em->persist($totalEstimadoLubricante);
            }

            $em->flush();
            $msg = $totalEstimadoLubricante;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'La asignación del total de litros de aceite a este medio de transporte ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el total de litros de aceite a este medio de transporte';
            }
        }
        return $msg;
    }

    public function agregarEstimadoLubricanteUnMes($data)
    {
        try {
            $em = $this->getEntityManager();

            $totalEstimadoLubricante = new PlanEstimadoMesLubricante();
            $totalEstimadoLubricante->setMes($data['mes']);
            $totalEstimadoLubricante->setLtsLubricante($data['ltsLubricante']);
            $importeLubricante = (float)($data['ltsLubricante'] * $data['precioLubricante']);

            $totalEstimadoLubricante->setImporteLubricante($importeLubricante);

            $trasporte = $em->getRepository('AppBundle:Transporte')->find($data['transporte']);
            $totalEstimadoLubricante->setTransporte($trasporte);

            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $totalEstimadoLubricante->setPlanEstimadoIndicadores($planEstimado);

            $em->persist($totalEstimadoLubricante);
            $em->flush();
            $msg = $totalEstimadoLubricante;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'La asignación del total de litros de aceite a este medio de transporte ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al asignar el total de litros de aceite a este medio de transporte';
            }
        }
        return $msg;
    }

    public function masterModificarTotalLubricanteMedioTransporteMes($data, $meses, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {

            if ($data['mes'] === '') {
                //agregar el
                $modificarLubricante = $this->modificarEstimadoLubricanteMes($data, $meses);
            } else {
                //agregar el
                $modificarLubricante = $this->modificarEstimadoLubricanteUnMes($data, $meses);
            }

            if (is_string($modificarLubricante)) {
                $em->rollback();
                return $modificarLubricante;
            }

            //se crea la traza
            $dataTraza = array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Modificar el total de  litros de aceite mensuales por tipo de combustible',
                'descripcion' => 'Se modificó el total mensual de litros de aceite para el transporte: '
            );
            $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);


            $em->commit();
            $msg = 'ok';


        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al modificar el total de litros de aceite a este medio de transporte';
        }
        return $msg;
    }

    public function modificarEstimadoLubricanteMes($data, $meses)
    {
        try {
            $em = $this->getEntityManager();

            $trasporte = $em->getRepository('AppBundle:Transporte')->find($data['transporte']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            $totalEstimadoLubricante = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->findBy(array('planEstimadoIndicadores' => $planEstimado, 'transporte' => $trasporte));

            if (!empty($totalEstimadoLubricante)) {

                foreach ($totalEstimadoLubricante as $mes) {
                    $em->remove($mes);
                }

                $em->flush();

                foreach ($meses as $mes) {
                    $totalEstimadoLubricante = new PlanEstimadoMesLubricante();
                    $totalEstimadoLubricante->setMes($mes['mes']);
                    $totalEstimadoLubricante->setLtsLubricante($data['ltsLubricante']);

                    $importeLubricante = (float)($data['ltsLubricante'] * $mes['precioLubricante']);

                    $totalEstimadoLubricante->setImporteLubricante($importeLubricante);
                    $totalEstimadoLubricante->setTransporte($trasporte);
                    $totalEstimadoLubricante->setPlanEstimadoIndicadores($planEstimado);
                    $em->persist($totalEstimadoLubricante);
                }
                $em->flush();
                $msg = $totalEstimadoLubricante;
            } else {
                $msg = $totalEstimadoLubricante;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el total de litros de aceite a este medio de transporte';
        }

        return $msg;

    }

    public function modificarEstimadoLubricanteUnMes($data, $meses)
    {
        try {
            $em = $this->getEntityManager();

            $trasporte = $em->getRepository('AppBundle:Transporte')->find($data['transporte']);
            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);
            $mesSeleccionado = $data['mes'];

            $totalEstimadoLubricante = $em->getRepository('AppBundle:PlanEstimadoMesLubricante')->findOneBy(array('mes' => $mesSeleccionado,'planEstimadoIndicadores' => $planEstimado, 'transporte' => $trasporte));

            if (!empty($totalEstimadoLubricante)) {

                foreach ($meses as $mes) {

                    if ($mesSeleccionado === $mes['mes']){
                        $totalEstimadoLubricante->setLtsLubricante($data['ltsLubricante']);

                        $importeLubricante = (float)($data['ltsLubricante'] * $mes['precioLubricante']);

                        $totalEstimadoLubricante->setImporteLubricante($importeLubricante);

                        $em->flush();
                    }
                }
                $msg = $totalEstimadoLubricante;
            } else {
                $msg = $totalEstimadoLubricante;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el total de litros de aceite a este medio de transporte';
        }

        return $msg;

    }

}
