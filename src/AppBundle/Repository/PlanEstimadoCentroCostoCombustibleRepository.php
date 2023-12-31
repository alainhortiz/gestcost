<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PlanEstimadoCentroCostoCombustible;
use Doctrine\ORM\EntityRepository;
use Exception;

/**
 * PlanEstimadoCentroCostoCombustibleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanEstimadoCentroCostoCombustibleRepository extends EntityRepository
{
    public function masterAgregarEstimadoCombustibleCentroCosto($data,$user)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try{

            //agregar el presupuesto mensual para las divisiones
            $estimadoCentroCosto = $this->agregarEstimadoCombustibleCentroCosto($data);

            if(is_string($estimadoCentroCosto)) {
                $em->rollback();
                $msg = $estimadoCentroCosto;
            } else{
                //se crea la traza
                $dataTraza = array(
                    'username' => $user->getUsername(),
                    'nombre' => $user->getNombre(),
                    'operacion' => 'Asignar el combustible de los medios de transportes de la división: '.$estimadoCentroCosto->getDivisionCentroCosto()->getNombre(),
                    'descripcion' => 'Se asignó el tipo de combustible: '.$estimadoCentroCosto->getTipoCombustible()->getNombre()
                );
                $em->getRepository('AppBundle:Traza')-> guardarTraza($dataTraza);
                $em->commit();
                $msg = 'ok';
            }

        }catch (Exception $e){

            $em->rollback();
            $msg = 'Se produjo un error al asignar el combustible de los medios de transportes';
        }
        return $msg;
    }

    public function agregarEstimadoCombustibleCentroCosto($data)
    {
        try {
            $em = $this->getEntityManager();

            $planEstimado = $em->getRepository('AppBundle:PlanEstimadoIndicadores')->find($data['idPlanEstimado']);

            foreach ($data['centroCostoDistribucion'] as $registro) {

                $transporte = $em->getRepository('AppBundle:Transporte')->find($registro['idTransporte']);

                if (!empty($transporte)) {
                    $divisionCentroCosto = $em->getRepository('AppBundle:DivisionCentroCosto')->find($transporte->getCentroCosto()->getDivisionCentroCosto());
                    $centroCosto = $em->getRepository('AppBundle:CentroCosto')->find($transporte->getCentroCosto());
                    $litros = (int) $registro['litros'];
                    $tipoCombustible = $em->getRepository('AppBundle:TipoCombustible')->find($transporte->getTipoCombustible());
                    if (!empty($tipoCombustible)) {
                        $precio = (float)$tipoCombustible->getPrecio();
                    }else{
                        return  'No existe el tipo de combustible';
                    }
                    $importe =  (float) ($precio * $litros);
                    $litrosEstimadoMedioTransporte = new PlanEstimadoCentroCostoCombustible();
                    $litrosEstimadoMedioTransporte->setMedioTRansporte($transporte);
                    $litrosEstimadoMedioTransporte->setTipoCombustible($tipoCombustible);
                    $litrosEstimadoMedioTransporte->setDivisionCentroCosto($divisionCentroCosto);
                    $litrosEstimadoMedioTransporte->setCentroCosto($centroCosto);
                    $litrosEstimadoMedioTransporte->setPrecio($precio);
                    $litrosEstimadoMedioTransporte->setLts($litros);
                    $litrosEstimadoMedioTransporte->setImporte($importe);
                    $litrosEstimadoMedioTransporte->setAprobarPrespuestoCentroCostoMesCombustible(true);
                    $litrosEstimadoMedioTransporte->setPlanEstimadoIndicadores($planEstimado);
                    $em->persist($litrosEstimadoMedioTransporte);
                }

            }
            $em->flush();
            $msg = $litrosEstimadoMedioTransporte;

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') > 0)
            {
                $msg = 'El total del presupuesto mensual para esta división ya existe, no se puede agregar';
            }
            else
            {
                $msg = 'Se produjo un error al asignar el presupuesto mensual para esta división';
            }
        }
        return $msg;
    }
}
