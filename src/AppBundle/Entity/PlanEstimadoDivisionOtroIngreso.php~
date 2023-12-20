<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoDivisionOtroIngreso
 *
 * @ORM\Table(name="plan_estimado_division_otro_ingreso",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA20", columns={"division_centro_costo_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoDivisionOtroIngresoRepository")
 */
class PlanEstimadoDivisionOtroIngreso
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMesOtroIngreso", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesOtroIngreso = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesOtroIngreso", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesOtroIngreso = false;

    /**
     * @var int
     *
     * @ORM\Column(name="totalOtroIngreso", type="integer",nullable=true)
     */
    private $totalOtroIngreso = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoOtrosIngresosDivision")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoOtrosIngresosDivision")
     */
    protected $planEstimadoIndicadores;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set aprobarPrespuestoDivisionMesOtroIngreso
     *
     * @param boolean $aprobarPrespuestoDivisionMesOtroIngreso
     *
     * @return PlanEstimadoDivisionOtroIngreso
     */
    public function setAprobarPrespuestoDivisionMesOtroIngreso($aprobarPrespuestoDivisionMesOtroIngreso)
    {
        $this->aprobarPrespuestoDivisionMesOtroIngreso = $aprobarPrespuestoDivisionMesOtroIngreso;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesOtroIngreso
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesOtroIngreso()
    {
        return $this->aprobarPrespuestoDivisionMesOtroIngreso;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesOtroIngreso
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesOtroIngreso
     *
     * @return PlanEstimadoDivisionOtroIngreso
     */
    public function setAprobarPrespuestoCentroCostoMesOtroIngreso($aprobarPrespuestoCentroCostoMesOtroIngreso)
    {
        $this->aprobarPrespuestoCentroCostoMesOtroIngreso = $aprobarPrespuestoCentroCostoMesOtroIngreso;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesOtroIngreso
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesOtroIngreso()
    {
        return $this->aprobarPrespuestoCentroCostoMesOtroIngreso;
    }

    /**
     * Set totalOtroIngreso
     *
     * @param integer $totalOtroIngreso
     *
     * @return PlanEstimadoDivisionOtroIngreso
     */
    public function setTotalOtroIngreso($totalOtroIngreso)
    {
        $this->totalOtroIngreso = $totalOtroIngreso;

        return $this;
    }

    /**
     * Get totalOtroIngreso
     *
     * @return integer
     */
    public function getTotalOtroIngreso()
    {
        return $this->totalOtroIngreso;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoDivisionOtroIngreso
     */
    public function setDivisionCentroCosto(\AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto = null)
    {
        $this->divisionCentroCosto = $divisionCentroCosto;

        return $this;
    }

    /**
     * Get divisionCentroCosto
     *
     * @return \AppBundle\Entity\DivisionCentroCosto
     */
    public function getDivisionCentroCosto()
    {
        return $this->divisionCentroCosto;
    }

    /**
     * Set planEstimadoIndicadores
     *
     * @param \AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores
     *
     * @return PlanEstimadoDivisionOtroIngreso
     */
    public function setPlanEstimadoIndicadores(\AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores = null)
    {
        $this->planEstimadoIndicadores = $planEstimadoIndicadores;

        return $this;
    }

    /**
     * Get planEstimadoIndicadores
     *
     * @return \AppBundle\Entity\PlanEstimadoIndicadores
     */
    public function getPlanEstimadoIndicadores()
    {
        return $this->planEstimadoIndicadores;
    }
}
