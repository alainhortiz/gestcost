<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanRealMes;
use AppBundle\Entity\PlanRealMesServicio;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanRealMesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanRealMesRepository extends EntityRepository
{

    public function masterAgregarRealMensual($data, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {

            //Agregar el plan real mensual
            $agregarRealMensual = $this->agregarRealMensual($data);

            if (is_string($agregarRealMensual)) {
                $em->rollback();
                return $agregarRealMensual;
            }

            //Se crea la traza
            $dataTraza = array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Agregar plan real mensual de un Centro de Costo',
                'descripcion' => 'Se agregó el plan real mensual a: ' . $agregarRealMensual->getCentroCosto()->getNombre()
            );
            $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);

            if (!empty($data['totalesServicios'])) {
                //Agregar los servicios de este plan real mensual
                $agregarRealMensualServicio = $this->agregarRealMensualServicios($agregarRealMensual->getId(), $data['totalesServicios']);

                if (is_string($agregarRealMensualServicio)) {
                    $em->rollback();
                    return $agregarRealMensualServicio;
                }

                //Se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Agregar los servicios al real mensual de un Centro de Costo',
                    'descripcion' => 'Se agregaron los servicios al real mensual'
                );
                $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);

            }

            $em->commit();
            $msg = 'ok';


        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al agregar el plan real mensual.';
        }
        return $msg;
    }

    public function agregarRealMensual($data)
    {
        try {
            $em = $this->getEntityManager();
            $realMensual = new PlanRealMes();
            $realMensual->setMes($data['mes']);

            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $realMensual->setCentroCosto($centroCosto);

            $planReal = $em->getRepository('AppBundle:PlanReal')->find($data['planReal']);
            $realMensual->setPlanReal($planReal);

            $realMensual->setVenta($data['ventaTotal']);
            $realMensual->setOtroIngreso($data['otroIngreso']);

            $ingresoTotal = $data['ventaTotal'] + $data['otroIngreso'];
            $realMensual->setIngresoTotal($ingresoTotal);

            $realMensual->setSalario($data['salario']);
            $realMensual->setVacaciones($data['vacaciones']);
            $fondoSalario = $data['salario'] + $data['vacaciones'];
            $realMensual->setFondoSalario($fondoSalario);

            $realMensual->setSeguridadSocial($data['seguridadSocial']);

            $impuestoFuerzaTrabajo = ($fondoSalario * 5) / 100;
            $realMensual->setImpuestoFuerzaTrabajo($impuestoFuerzaTrabajo);

            $gastoFuerzaTrabajo = $fondoSalario + $data['seguridadSocial'] + $impuestoFuerzaTrabajo;
            $realMensual->setGastoFuerzaTrabajo($gastoFuerzaTrabajo);

            $realMensual->setMateriaPrima($data['materiaPrima']);
            $realMensual->setCombustibleLubricante($data['combustibleLubricante']);
            $realMensual->setEnergia($data['energia']);

            $gastoMaterial = $data['materiaPrima'] + $data['combustibleLubricante'] + $data['energia'];
            $realMensual->setGastoMaterial($gastoMaterial);

            $realMensual->setDepreciacion($data['depreciacion']);
            $realMensual->setOtroGasto($data['otroGasto']);
            $realMensual->setGastoFinanciero($data['gastoFinanciero']);
            $realMensual->setImpuestoTerrestre($data['impuestoTerrestre']);
            $realMensual->setOtroGastoMonetario($data['otroGastoMonetario']);
            $realMensual->setGastoComedor($data['gastoComedor']);
            $realMensual->setGastoxPerdida($data['gastoxPerdida']);

            $gastoTotal = $gastoFuerzaTrabajo + $gastoMaterial + $data['otroGasto'] + $data['depreciacion'] + $data['gastoFinanciero'];
            $realMensual->setGastoTotal($gastoTotal);

            $utilidad = $ingresoTotal - $gastoTotal;
            $realMensual->setUtilidad($utilidad);

            $gastoTotalIngresoTotal = 0;

            if ($ingresoTotal != 0) {
                $gastoTotalIngresoTotal = $gastoTotal / $ingresoTotal;
            }
            $realMensual->setGastoTotalIngresoTotal($gastoTotalIngresoTotal);

            $em->persist($realMensual);
            $em->flush();
            $msg = $realMensual;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0) {
                $msg = 'El real mensual de este centro de costo ya existe, no se puede agregar';
            } else {
                $msg = 'Se produjo un error al agregar el real mensual de este centro de costo';
            }
        }
        return $msg;
    }

    public function agregarRealMensualServicios($idPlanRealMes, $servicios)
    {
        try {
            $em = $this->getEntityManager();

            $servicioRealMes = '';
            $servicioRecibido = 0;

            if($servicios){
                foreach ($servicios as $serv) {

                    $servicioRealMes = new PlanRealMesServicio();

                    $planRealMes = $em->getRepository('AppBundle:PlanRealMes')->find($idPlanRealMes);
                    if (!empty($planRealMes)) {
                        $servicioRealMes->setPlanRealMes($planRealMes);
                    }else{
                        return $planRealMes;
                    }

                    $servicio = $em->getRepository('AppBundle:OtroGasto')->find($serv['servicio']);
                    if (!empty($servicio)) {
                        $servicioRealMes->setOtroGasto($servicio);
                    }else{
                        return $servicio;
                    }

                    $servicioRealMes->setTotal($serv['total']);
                    $servicioRecibido += $serv['total'];

                    $em->persist($servicioRealMes);

                }
                $em->flush();
            }
            $planRealMes = $em->getRepository('AppBundle:PlanRealMes')->find($idPlanRealMes);
            if (!empty($planRealMes)) {
                $planRealMes->setServicioRecibido($servicioRecibido);
                $em->flush();
            }
            $msg = $servicioRealMes;

        } catch (Exception $e) {

            $msg = 'Se produjo un error al insertar los servicios recibidos.';
        }
        return $msg;
    }

    public function masterModificarRealMensual($data, $user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {

            //Modificar el plan real mensual
            $modificarRealMensual = $this->modificarRealMensual($data);

            if (is_string($modificarRealMensual)) {
                $em->rollback();
                return $modificarRealMensual;
            }

            //Se crea la traza
            $dataTraza = array(
                'username' => $user->getUsername(),
                'nombre' => $user->getNombre(),
                'operacion' => 'Modificar plan real mensual de un Centro de Costo',
                'descripcion' => 'Se modificó el plan real mensual a: ' . $modificarRealMensual->getCentroCosto()->getNombre()
            );
            $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);

            if (!empty($data['totalesServicios'])) {
                //Agregar los servicios de este plan real mensual
                $modificarRealMensualServicio = $this->modificarRealMensualServicios($modificarRealMensual->getId(), $data['totalesServicios']);

                if (is_string($modificarRealMensualServicio)) {
                    $em->rollback();
                    return $modificarRealMensualServicio;
                }

                //Se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Modificar los servicios al real mensual de un Centro de Costo',
                    'descripcion' => 'Se modificaron los servicios al real mensual'
                );
                $em->getRepository('AppBundle:Traza')->guardarTraza($dataTraza);
            }

            $em->commit();
            $msg = 'ok';


        } catch (Exception $e) {

            $em->rollback();
            $msg = 'Se produjo un error al modificar el plan real mensual';
        }
        return $msg;
    }

    public function modificarRealMensual($data)
    {
        try {
            $em = $this->getEntityManager();

            $realMes = $em->getRepository('AppBundle:PlanRealMes')->find($data['id']);

            if (!empty($realMes)) {

                $realMes->setVenta($data['ventaTotal']);
                $realMes->setOtroIngreso($data['otroIngreso']);

                $ingresoTotal = $data['ventaTotal'] + $data['otroIngreso'];
                $realMes->setIngresoTotal($ingresoTotal);

                $realMes->setSalario($data['salario']);
                $realMes->setVacaciones($data['vacaciones']);
                $fondoSalario = $data['salario'] + $data['vacaciones'];
                $realMes->setFondoSalario($fondoSalario);

                $realMes->setSeguridadSocial($data['seguridadSocial']);

                $impuestoFuerzaTrabajo = ($fondoSalario * 5) / 100;
                $realMes->setImpuestoFuerzaTrabajo($impuestoFuerzaTrabajo);

                $gastoFuerzaTrabajo = $fondoSalario + $data['seguridadSocial'] + $impuestoFuerzaTrabajo;
                $realMes->setGastoFuerzaTrabajo($gastoFuerzaTrabajo);

                $realMes->setMateriaPrima($data['materiaPrima']);
                $realMes->setCombustibleLubricante($data['combustibleLubricante']);
                $realMes->setEnergia($data['energia']);

                $gastoMaterial = $data['materiaPrima'] + $data['combustibleLubricante'] + $data['energia'];
                $realMes->setGastoMaterial($gastoMaterial);

                $realMes->setDepreciacion($data['depreciacion']);
                $realMes->setOtroGasto($data['otroGasto']);
                $realMes->setGastoFinanciero($data['gastoFinanciero']);
                $realMes->setImpuestoTerrestre($data['impuestoTerrestre']);
                $realMes->setOtroGastoMonetario($data['otroGastoMonetario']);
                $realMes->setGastoComedor($data['gastoComedor']);
                $realMes->setGastoxPerdida($data['gastoxPerdida']);

                $gastoTotal = $gastoFuerzaTrabajo + $gastoMaterial + $data['otroGasto'] + $data['depreciacion'] + $data['gastoFinanciero'];
                $realMes->setGastoTotal($gastoTotal);

                $utilidad = $ingresoTotal - $gastoTotal;
                $realMes->setUtilidad($utilidad);

                $gastoTotalIngresoTotal = 0;

                if ($ingresoTotal != 0) {
                    $gastoTotalIngresoTotal = $gastoTotal / $ingresoTotal;
                }
                $realMes->setGastoTotalIngresoTotal($gastoTotalIngresoTotal);

                $em->flush();
                $msg = $realMes;
            } else {
                $msg = $realMes;
            }

        } catch (Exception $e) {
            $msg = 'Se produjo un error al modificar el real mensual para este Centro de Costo';
        }

        return $msg;
    }

    public function modificarRealMensualServicios($idPlanRealMes, $servicios)
    {
        try {
            $em = $this->getEntityManager();

            $servicioRecibido = 0;

            $servicioRealMes = $em->getRepository('AppBundle:PlanRealMesServicio')->findBy(array('planRealMes' => $idPlanRealMes));
            if (!empty($servicioRealMes)) {
                foreach ($servicioRealMes as $serv) {
                    $em->remove($serv);
                }
                $em->flush();
            }
            if ($servicios) {
                foreach ($servicios as $serv) {

                    $servicioRealMes = new PlanRealMesServicio();

                    $planRealMes = $em->getRepository('AppBundle:PlanRealMes')->find($idPlanRealMes);
                    if (!empty($planRealMes)) {
                        $servicioRealMes->setPlanRealMes($planRealMes);
                    } else {
                        return $planRealMes;
                    }

                    $servicio = $em->getRepository('AppBundle:OtroGasto')->find($serv['servicio']);
                    if (!empty($servicio)) {
                        $servicioRealMes->setOtroGasto($servicio);
                    } else {
                        return $servicio;
                    }

                    $servicioRealMes->setTotal($serv['total']);
                    $servicioRecibido += $serv['total'];

                    $em->persist($servicioRealMes);

                }
                $em->flush();
            }
            $planRealMes = $em->getRepository('AppBundle:PlanRealMes')->find($idPlanRealMes);
            if (!empty($planRealMes)) {
                $planRealMes->setServicioRecibido($servicioRecibido);
                $em->flush();
            }
            $msg = $servicioRealMes;

        } catch (Exception $e) {

            $msg = 'Se produjo un error al modificar los servicios recibidos.';
        }
        return $msg;
    }

    public function eliminarRealMensual($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $realMensual = $em->getRepository('AppBundle:PlanRealMes')->find($id);

            if (!empty($realMensual)) {
                $em->remove($realMensual);
                $em->flush();
                $msg = $realMensual;
            } else {
                $msg = $realMensual;
            }

        } catch (Exception $e) {

            if (strpos($e->getMessage(), 'foreign key') > 0) {
                $msg = 'Existen datos asociados a este plan real mensual, no se puede eliminar';
            } else {
                $msg = 'Se produjo un error al eliminar el real mensual del Centro de Costo';
            }
        }
        return $msg;
    }

    // Datos para exportar a Excel
    public function datosExportRealDivision($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, SUM(pm.venta) as venta, SUM(pm.otroIngreso) as otroIngreso, SUM(pm.ingresoTotal) as ingresoTotal, 
                SUM(pm.gastoTotal) as gastoTotal, SUM(pm.utilidad) as utilidad, SUM(pm.gastoFuerzaTrabajo) as gastoFuerzaTrabajo, SUM(pm.fondoSalario) as fondoSalario,
                SUM(pm.salario) as salario, SUM(pm.vacaciones) as vacaciones, SUM(pm.seguridadSocial) as seguridadSocial, SUM(pm.impuestoFuerzaTrabajo) as impuestoFuerzaTrabajo,  
                SUM(pm.gastoMaterial) as gastoMaterial, SUM(pm.materiaPrima) as materiaPrima, SUM(pm.combustibleLubricante) as combustibleLubricante, SUM(pm.energia) as energia,
                SUM(pm.depreciacion) as depreciacion, SUM(pm.otroGasto) as otroGasto, SUM(pm.servicioRecibido) as servicioRecibido,
                SUM(pm.gastoFinanciero) as gastoFinanciero, SUM(pm.impuestoTerrestre) as impuestoTerrestre, SUM(pm.otroGastoMonetario) as otroGastoMonetario,
                SUM(pm.gastoComedor) as gastoComedor, SUM(pm.gastoxPerdida) as gastoxPerdida, SUM(pm.gastoTotalIngresoTotal) as gastoTotalIngresoTotal,
                SUM(pm.gastoSalarioTotalProduccionTotal) as gastoSalarioTotalProduccionTotal, SUM(pm.gastoSalarioDirectoProduccionTotal) as gastoSalarioDirectoProduccionTotal, 
                SUM(pm.gastoSalarioIndAdmProduccionTotal) as gastoSalarioIndAdmProduccionTotal, SUM(pm.costoPPesoVentaTotal) as costoPPesoVentaTotal, 
                SUM(pm.gastoTotalPIngresoTotal) as gastoTotalPIngresoTotal, SUM(pm.productividadMensualSVentaTotal) as productividadMensualSVentaTotal,
                SUM(pm.perdidaAlimento) as perdidaAlimento, SUM(pm.valorAgregadoBruto) as valorAgregadoBruto, SUM(pm.utilidadPIngresosTotales) as utilidadPIngresosTotales
                FROM AppBundle:PlanRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.year = :p1
                GROUP BY d.nombre
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealDivisionServiciosEncabezados($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT s.nombre servicio,s.codigo
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                WHERE p.year = :p1
                ORDER BY s.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealDivisionServicios($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, s.nombre servicio, SUM(pms.total) as total
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.year = :p1
                GROUP BY d.nombre,s.nombre
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealDivisionMes($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, pm.mes, SUM(pm.venta) as venta, SUM(pm.otroIngreso) as otroIngreso, SUM(pm.ingresoTotal) as ingresoTotal, 
                SUM(pm.gastoTotal) as gastoTotal, SUM(pm.utilidad) as utilidad, SUM(pm.gastoFuerzaTrabajo) as gastoFuerzaTrabajo, SUM(pm.fondoSalario) as fondoSalario,
                SUM(pm.salario) as salario, SUM(pm.vacaciones) as vacaciones, SUM(pm.seguridadSocial) as seguridadSocial, SUM(pm.impuestoFuerzaTrabajo) as impuestoFuerzaTrabajo, 
                SUM(pm.gastoMaterial) as gastoMaterial, SUM(pm.materiaPrima) as materiaPrima, SUM(pm.combustibleLubricante) as combustibleLubricante, SUM(pm.energia) as energia,
                SUM(pm.depreciacion) as depreciacion, SUM(pm.otroGasto) as otroGasto, SUM(pm.servicioRecibido) as servicioRecibido,
                SUM(pm.gastoFinanciero) as gastoFinanciero, SUM(pm.impuestoTerrestre) as impuestoTerrestre, SUM(pm.otroGastoMonetario) as otroGastoMonetario,
                SUM(pm.gastoComedor) as gastoComedor, SUM(pm.gastoxPerdida) as gastoxPerdida, SUM(pm.gastoTotalIngresoTotal) as gastoTotalIngresoTotal,
                SUM(pm.gastoSalarioTotalProduccionTotal) as gastoSalarioTotalProduccionTotal, SUM(pm.gastoSalarioDirectoProduccionTotal) as gastoSalarioDirectoProduccionTotal, 
                SUM(pm.gastoSalarioIndAdmProduccionTotal) as gastoSalarioIndAdmProduccionTotal, SUM(pm.costoPPesoVentaTotal) as costoPPesoVentaTotal, 
                SUM(pm.gastoTotalPIngresoTotal) as gastoTotalPIngresoTotal, SUM(pm.productividadMensualSVentaTotal) as productividadMensualSVentaTotal,
                SUM(pm.perdidaAlimento) as perdidaAlimento, SUM(pm.valorAgregadoBruto) as valorAgregadoBruto, SUM(pm.utilidadPIngresosTotales) as utilidadPIngresosTotales
                FROM AppBundle:PlanRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.year = :p1
                GROUP BY d.nombre, pm.mes
                ORDER BY pm.mes, d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealDivisionMesServicios($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, pm.mes, s.nombre servicio, SUM(pms.total) as total
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                JOIN c.divisionCentroCosto d
                WHERE p.year = :p1
                GROUP BY d.nombre,pm.mes,s.nombre
                ORDER BY d.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealDivisionMeses($year)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT pm.mes
                FROM AppBundle:PlanRealMes pm
                JOIN pm.planReal p
                WHERE p.year = :p1
                ORDER BY pm.mes';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $year);

        return $query->getResult();

    }

    public function datosExportRealCentroCosto($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, SUM(pm.venta) as venta, SUM(pm.otroIngreso) as otroIngreso, SUM(pm.ingresoTotal) as ingresoTotal, 
                SUM(pm.gastoTotal) as gastoTotal, SUM(pm.utilidad) as utilidad, SUM(pm.gastoFuerzaTrabajo) as gastoFuerzaTrabajo, SUM(pm.fondoSalario) as fondoSalario,
                SUM(pm.salario) as salario, SUM(pm.vacaciones) as vacaciones, SUM(pm.seguridadSocial) as seguridadSocial, SUM(pm.impuestoFuerzaTrabajo) as impuestoFuerzaTrabajo, 
                SUM(pm.gastoMaterial) as gastoMaterial, SUM(pm.materiaPrima) as materiaPrima, SUM(pm.combustibleLubricante) as combustibleLubricante, SUM(pm.energia) as energia,
                SUM(pm.depreciacion) as depreciacion, SUM(pm.otroGasto) as otroGasto, SUM(pm.servicioRecibido) as servicioRecibido,
                SUM(pm.gastoFinanciero) as gastoFinanciero, SUM(pm.impuestoTerrestre) as impuestoTerrestre, SUM(pm.otroGastoMonetario) as otroGastoMonetario,
                SUM(pm.gastoComedor) as gastoComedor, SUM(pm.gastoxPerdida) as gastoxPerdida, SUM(pm.gastoTotalIngresoTotal) as gastoTotalIngresoTotal,
                SUM(pm.gastoSalarioTotalProduccionTotal) as gastoSalarioTotalProduccionTotal, SUM(pm.gastoSalarioDirectoProduccionTotal) as gastoSalarioDirectoProduccionTotal, 
                SUM(pm.gastoSalarioIndAdmProduccionTotal) as gastoSalarioIndAdmProduccionTotal, SUM(pm.costoPPesoVentaTotal) as costoPPesoVentaTotal, 
                SUM(pm.gastoTotalPIngresoTotal) as gastoTotalPIngresoTotal, SUM(pm.productividadMensualSVentaTotal) as productividadMensualSVentaTotal,
                SUM(pm.perdidaAlimento) as perdidaAlimento, SUM(pm.valorAgregadoBruto) as valorAgregadoBruto, SUM(pm.utilidadPIngresosTotales) as utilidadPIngresosTotales
                FROM AppBundle:PlanRealMes pm
                JOIN pm.centroCosto c
                WHERE pm.id = :p1
                GROUP BY c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

    public function datosExportRealCentroCostoServiciosEncabezados($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT s.nombre servicio, s.codigo
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                WHERE pm.id = :p1
                ORDER BY s.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

    public function datosExportRealCentroCostoServicios($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, s.nombre servicio, SUM(pms.total) as total
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                WHERE pm.id = :p1
                GROUP BY c.nombre,s.nombre
                ORDER BY c.nombre';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

    public function datosExportRealCentroCostoMes($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, pm.mes, SUM(pm.venta) as venta, SUM(pm.otroIngreso) as otroIngreso, SUM(pm.ingresoTotal) as ingresoTotal, 
                SUM(pm.gastoTotal) as gastoTotal, SUM(pm.utilidad) as utilidad, SUM(pm.gastoFuerzaTrabajo) as gastoFuerzaTrabajo, SUM(pm.fondoSalario) as fondoSalario,
                SUM(pm.salario) as salario, SUM(pm.vacaciones) as vacaciones, SUM(pm.seguridadSocial) as seguridadSocial, SUM(pm.impuestoFuerzaTrabajo) as impuestoFuerzaTrabajo, 
                SUM(pm.gastoMaterial) as gastoMaterial, SUM(pm.materiaPrima) as materiaPrima, SUM(pm.combustibleLubricante) as combustibleLubricante, SUM(pm.energia) as energia,
                SUM(pm.depreciacion) as depreciacion, SUM(pm.otroGasto) as otroGasto, SUM(pm.servicioRecibido) as servicioRecibido,
                SUM(pm.gastoFinanciero) as gastoFinanciero, SUM(pm.impuestoTerrestre) as impuestoTerrestre, SUM(pm.otroGastoMonetario) as otroGastoMonetario,
                SUM(pm.gastoComedor) as gastoComedor, SUM(pm.gastoxPerdida) as gastoxPerdida, SUM(pm.gastoTotalIngresoTotal) as gastoTotalIngresoTotal,
                SUM(pm.gastoSalarioTotalProduccionTotal) as gastoSalarioTotalProduccionTotal, SUM(pm.gastoSalarioDirectoProduccionTotal) as gastoSalarioDirectoProduccionTotal, 
                SUM(pm.gastoSalarioIndAdmProduccionTotal) as gastoSalarioIndAdmProduccionTotal, SUM(pm.costoPPesoVentaTotal) as costoPPesoVentaTotal, 
                SUM(pm.gastoTotalPIngresoTotal) as gastoTotalPIngresoTotal, SUM(pm.productividadMensualSVentaTotal) as productividadMensualSVentaTotal,
                SUM(pm.perdidaAlimento) as perdidaAlimento, SUM(pm.valorAgregadoBruto) as valorAgregadoBruto, SUM(pm.utilidadPIngresosTotales) as utilidadPIngresosTotales
                FROM AppBundle:PlanRealMes pm
                JOIN pm.centroCosto c
                WHERE pm.id = :p1
                GROUP BY c.nombre, pm.mes
                ORDER BY pm.mes, c.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

    public function datosExportRealCentroCostoMesServicios($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT c.nombre as centro, pm.mes, s.nombre servicio, SUM(pms.total) as total
                FROM AppBundle:PlanRealMesServicio pms
                JOIN pms.otroGasto s
                JOIN pms.planRealMes pm
                JOIN pm.planReal p
                JOIN pm.centroCosto c
                WHERE pm.id = :p1
                GROUP BY c.nombre,pm.mes,s.nombre
                ORDER BY pm.mes, c.id';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

    public function datosExportRealCentroCostoMeses($id)
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT pm.mes
                FROM AppBundle:PlanRealMes pm
                WHERE pm.id = :p1
                ORDER BY pm.mes';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', $id);

        return $query->getResult();

    }

}
