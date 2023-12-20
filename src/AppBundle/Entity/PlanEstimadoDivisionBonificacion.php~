<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoDivisionBonificacion
 *
 * @ORM\Table(name="plan_estimado_division_bonificacion",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA56", columns={"division_centro_costo_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoDivisionBonificacionRepository")
 */
class PlanEstimadoDivisionBonificacion
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
     * @ORM\Column(name="aprobarPrespuestoDivisionMesBonificacion", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesBonificacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesBonificacion", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesBonificacion = false;

    /**
     * @var int
     *
     * @ORM\Column(name="totalBonificacion", type="integer",nullable=true)
     */
    private $totalBonificacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoBonificacionesDivision")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoBonificacionesDivision")
     */
    protected $planEstimadoIndicadores;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set aprobarPrespuestoDivisionMesBonificacion
     *
     * @param boolean $aprobarPrespuestoDivisionMesBonificacion
     *
     * @return PlanEstimadoDivisionBonificacion
     */
    public function setAprobarPrespuestoDivisionMesBonificacion($aprobarPrespuestoDivisionMesBonificacion)
    {
        $this->aprobarPrespuestoDivisionMesBonificacion = $aprobarPrespuestoDivisionMesBonificacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesBonificacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesBonificacion()
    {
        return $this->aprobarPrespuestoDivisionMesBonificacion;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesBonificacion
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesBonificacion
     *
     * @return PlanEstimadoDivisionBonificacion
     */
    public function setAprobarPrespuestoCentroCostoMesBonificacion($aprobarPrespuestoCentroCostoMesBonificacion)
    {
        $this->aprobarPrespuestoCentroCostoMesBonificacion = $aprobarPrespuestoCentroCostoMesBonificacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesBonificacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesBonificacion()
    {
        return $this->aprobarPrespuestoCentroCostoMesBonificacion;
    }

    /**
     * Set totalBonificacion
     *
     * @param integer $totalBonificacion
     *
     * @return PlanEstimadoDivisionBonificacion
     */
    public function setTotalBonificacion($totalBonificacion)
    {
        $this->totalBonificacion = $totalBonificacion;

        return $this;
    }

    /**
     * Get totalBonificacion
     *
     * @return integer
     */
    public function getTotalBonificacion()
    {
        return $this->totalBonificacion;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoDivisionBonificacion
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
     * @return PlanEstimadoDivisionBonificacion
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
