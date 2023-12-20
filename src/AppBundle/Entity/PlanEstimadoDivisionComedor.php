<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoDivisionComedor
 *
 * @ORM\Table(name="plan_estimado_division_comedor",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA61", columns={"division_centro_costo_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoDivisionComedorRepository")
 */
class PlanEstimadoDivisionComedor
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
     * @ORM\Column(name="aprobarPrespuestoDivisionMesComedor", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesComedor = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesComedor", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesComedor = false;

    /**
     * @var int
     *
     * @ORM\Column(name="totalComedor", type="integer",nullable=true)
     */
    private $totalComedor = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoComedoresDivision")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoComedoresDivision")
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
     * Set aprobarPrespuestoDivisionMesComedor
     *
     * @param boolean $aprobarPrespuestoDivisionMesComedor
     *
     * @return PlanEstimadoDivisionComedor
     */
    public function setAprobarPrespuestoDivisionMesComedor($aprobarPrespuestoDivisionMesComedor)
    {
        $this->aprobarPrespuestoDivisionMesComedor = $aprobarPrespuestoDivisionMesComedor;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesComedor
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesComedor()
    {
        return $this->aprobarPrespuestoDivisionMesComedor;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesComedor
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesComedor
     *
     * @return PlanEstimadoDivisionComedor
     */
    public function setAprobarPrespuestoCentroCostoMesComedor($aprobarPrespuestoCentroCostoMesComedor)
    {
        $this->aprobarPrespuestoCentroCostoMesComedor = $aprobarPrespuestoCentroCostoMesComedor;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesComedor
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesComedor()
    {
        return $this->aprobarPrespuestoCentroCostoMesComedor;
    }

    /**
     * Set totalComedor
     *
     * @param integer $totalComedor
     *
     * @return PlanEstimadoDivisionComedor
     */
    public function setTotalComedor($totalComedor)
    {
        $this->totalComedor = $totalComedor;

        return $this;
    }

    /**
     * Get totalComedor
     *
     * @return integer
     */
    public function getTotalComedor()
    {
        return $this->totalComedor;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoDivisionComedor
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
     * @return PlanEstimadoDivisionComedor
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
