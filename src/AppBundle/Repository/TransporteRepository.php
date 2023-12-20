<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Transporte;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * TransporteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransporteRepository extends EntityRepository
{
    public function agregarTransporte($data)
    {
        try{
            $em = $this->getEntityManager();
            $transporte = new Transporte();
            $transporte->setMatricula($data['matricula']);
            $transporte->setNoActivoFijo($data['noActivoFijo']);
            $transporte->setYear($data['year']);
            $transporte->setColor($data['color']);
            $transporte->setChasis($data['chasis']);
            $transporte->setMotor($data['motor']);
            $transporte->setValor($data['valor']);
            $transporte->setCirculacion($data['circulacion']);
            $transporte->setIsLubricante($data['lubricante']);
            $transporte->setIsActive($data['activo']);

            $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
            $transporte->setCentroCosto($centroCosto);

            $modeloTransporte = $em->getRepository('AppBundle:ModeloTransporte')->find($data['modeloTransporte']);
            $transporte->setModeloTransporte($modeloTransporte);

            $tipoCombustible = $em->getRepository('AppBundle:TipoCombustible')->find($data['tipoCombustible']);
            $transporte->setTipoCombustible($tipoCombustible);

            if ($data['lubricante'] === '1') {
                $tipoLubricante = $em->getRepository('AppBundle:Lubricante')->find($data['tipoLubricante']);
                $transporte->setLubricante($tipoLubricante);
            }

            $em->persist($transporte);
            $em->flush();
            $msg = $transporte;

        }catch (Exception $e)
        {
            if(strpos($e->getMessage() , 'Duplicate entry') > 0)
            {
                $msg = 'El medio de transporte ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al insertar el medio de transporte';
            }
        }
        return $msg;
    }

    public function modificarTransporte($data)
    {
        try
        {
            $em = $this->getEntityManager();
            $transporte = $em->getRepository('AppBundle:Transporte')->find($data['idTransporte']);

            if (!empty($transporte)) {
                $transporte->setMatricula($data['matricula']);
                $transporte->setNoActivoFijo($data['noActivoFijo']);
                $transporte->setYear($data['year']);
                $transporte->setColor($data['color']);
                $transporte->setChasis($data['chasis']);
                $transporte->setMotor($data['motor']);
                $transporte->setValor($data['valor']);
                $transporte->setCirculacion($data['circulacion']);
                $transporte->setIsLubricante($data['lubricante']);

                $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($data['centroCosto']);
                $transporte->setCentroCosto($centroCosto);

                $modeloTransporte = $em->getRepository('AppBundle:ModeloTransporte')->find($data['modeloTransporte']);
                $transporte->setModeloTransporte($modeloTransporte);

                $tipoCombustible = $em->getRepository('AppBundle:TipoCombustible')->find($data['tipoCombustible']);
                $transporte->setTipoCombustible($tipoCombustible);

                if ($data['lubricante'] === '1') {
                    $tipoLubricante = $em->getRepository('AppBundle:Lubricante')->find($data['tipoLubricante']);
                    $transporte->setLubricante($tipoLubricante);
                }

                $em->persist($transporte);
                $em->flush();
                $msg = $transporte;
            } else {
                $msg = $transporte;
            }

        }catch (Exception $e)
        {
            $msg = 'Se produjo un error al modificar el medio de Transporte';
        }

        return $msg;
    }

    public function eliminarTransporte($id)
    {
        try
        {
            $em = $this->getEntityManager();
            $transporte = $em->getRepository('AppBundle:Transporte')->find($id);

            if (!empty($transporte)) {
                $transporte->setIsActive(0);
                $em->persist($transporte);
                $em->flush();
                $msg = $transporte;
            } else {
                $msg = $transporte;
            }

        }catch (Exception $e){

            $msg = 'Se produjo un error al desactivar el medio de Transporte';

        }
        return $msg;
    }

    public function tipoCombustibleDivision()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT d.id as idDivision, t.id as tipoIdCombustible, t.nombre as tipoCombustible, t.precio
                FROM AppBundle:Transporte e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.tipoCombustible t
                ORDER BY d.id, t.id';

        return $em->createQuery($dql)->getResult();

    }

    public function localizarInsertarTransportes($tipo,$division)
    {
        $em = $this->getEntityManager();

        if($tipo ==='Aceites y Lubricantes') {
            $dql = 'SELECT e.id, c.nombre as centro, e.noActivoFijo as activo, p.nombre as tipo, m.nombre as modelo, e.year, e.color, e.valor, e.matricula, e.chasis, e.motor, e.circulacion
                FROM AppBundle:Transporte e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte p
                JOIN e.tipoCombustible t
                WHERE e.isLubricante =:p1
                AND d.nombre =:p2
                ORDER BY c.nombre,p.nombre,m.nombre';
            $query = $em->createQuery($dql);
            $query->setParameter('p1', true);
            $query->setParameter('p2', $division);
        } else {
            $dql = 'SELECT e.id, c.nombre as centro, e.noActivoFijo as activo, p.nombre as tipo, m.nombre as modelo, e.color, e.matricula 
                FROM AppBundle:Transporte e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte p
                JOIN e.tipoCombustible t
                WHERE t.nombre =:p1
                AND d.nombre =:p2
                ORDER BY c.nombre,p.nombre,m.nombre';
            $query = $em->createQuery($dql);
            $query->setParameter('p1', $tipo);
            $query->setParameter('p2', $division);
        }

        return $query->getResult();

    }

    public function localizarMostrarTransportes($tipo,$division)
    {
        $em = $this->getEntityManager();

        if($tipo ==='Aceites y Lubricantes') {
            $dql = 'SELECT e.id, c.nombre as centro, f.noActivoFijo as activo, p.nombre as tipo, m.nombre as modelo, f.color, f.matricula, e.lts
                FROM AppBundle:PlanEstimadoCentroCostoCombustible e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.medioTRansporte f
                JOIN f.modeloTransporte m
                JOIN m.tipoTransporte p
                JOIN f.tipoCombustible t
                WHERE f.isLubricante =:p1
                AND d.nombre =:p2
                ORDER BY c.nombre,p.nombre,m.nombre';
            $query = $em->createQuery($dql);
            $query->setParameter('p1', true);
            $query->setParameter('p2', $division);
        } else {
            $dql = 'SELECT e.id, c.nombre as centro, f.noActivoFijo as activo, p.nombre as tipo, m.nombre as modelo, f.color, f.matricula, e.lts
                FROM AppBundle:PlanEstimadoCentroCostoCombustible e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.medioTRansporte f
                JOIN f.modeloTransporte m
                JOIN m.tipoTransporte p
                JOIN f.tipoCombustible t
                WHERE t.nombre =:p1
                AND d.nombre =:p2
                ORDER BY c.nombre,p.nombre,m.nombre';
            $query = $em->createQuery($dql);
            $query->setParameter('p1', $tipo);
            $query->setParameter('p2', $division);
        }

        return $query->getResult();

    }

    public function estimadoTipoTransporte()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT t.id, t.nombre, c.id as idTipoCombustible
                FROM AppBundle:Transporte e
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte t
                JOIN e.tipoCombustible c';

        return $em->createQuery($dql)->getResult();

    }

    public function estimadoLubricanteTipoTransporte()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT t.id, t.nombre, c.id as idTipoLubricante
                FROM AppBundle:Transporte e
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte t
                JOIN e.lubricante c
                WHERE e.isLubricante =:p1';

        $query = $em->createQuery($dql);
        $query->setParameter('p1', true);

        return $query->getResult();

    }

    // Datos para exportar a Excel
    public function datosExportTransportes()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT d.nombre as division, c.nombre as centro, e.noActivoFijo as activo, p.nombre as tipo, t.nombre as tipoCombustible, e.isLubricante as lubricante, m.nombre as modelo, e.year, e.color as Color, e.valor as valor, e.matricula, e.chasis as Chasis, e.motor as Motor, e.circulacion
                FROM AppBundle:Transporte e
                JOIN e.centroCosto c
                JOIN c.divisionCentroCosto d
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte p
                JOIN e.tipoCombustible t
                ORDER BY d.nombre,c.nombre,p.nombre,m.nombre';

        return $em->createQuery($dql)->getResult();

    }

    public function datosExportTiposModelo()
    {
        $em = $this->getEntityManager();

        $dql = 'SELECT t.nombre as tipo, m.nombre as modelo, count(e) as cantidad
                FROM AppBundle:Transporte e
                JOIN e.modeloTransporte m
                JOIN m.tipoTransporte t
                GROUP BY t.nombre, m.nombre 
                ORDER BY t.nombre';

        return $em->createQuery($dql)->getResult();

    }
}