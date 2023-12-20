<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoDivisionSalario
 *
 * @ORM\Table(name="plan_estimado_division_salario",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA03", columns={"division_centro_costo_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoDivisionSalarioRepository")
 */
class PlanEstimadoDivisionSalario
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
     * @ORM\Column(name="aprobarPrespuestoDivisionMesFondo", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesFondo = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesFondo", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesFondo = false;

    /**
     * @var int
     *
     * @ORM\Column(name="totalSalarioDivision", type="integer",nullable=true)
     */
    private $totalSalarioDivision = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPromedioTrabajador", type="integer",nullable=true)
     */
    private $totalPromedioTrabajador = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalGastoSalarioPesoProduccion", type="integer",nullable=true)
     */
    private $totalGastoSalarioPesoProduccion = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalSalarioMedio", type="integer",nullable=true)
     */
    private $totalSalarioMedio = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioDirecto", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioDirecto = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioIndirecto", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioIndirecto = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioAdministrativo", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioAdministrativo = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoRecursosHumanos")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoRecursosHumanos")
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
     * Set aprobarPrespuestoDivisionMesFondo
     *
     * @param boolean $aprobarPrespuestoDivisionMesFondo
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setAprobarPrespuestoDivisionMesFondo($aprobarPrespuestoDivisionMesFondo)
    {
        $this->aprobarPrespuestoDivisionMesFondo = $aprobarPrespuestoDivisionMesFondo;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesFondo
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesFondo()
    {
        return $this->aprobarPrespuestoDivisionMesFondo;
    }


    /**
     * Set aprobarPrespuestoCentroCostoMesFondo
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesFondo
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setAprobarPrespuestoCentroCostoMesFondo($aprobarPrespuestoCentroCostoMesFondo)
    {
        $this->aprobarPrespuestoCentroCostoMesFondo = $aprobarPrespuestoCentroCostoMesFondo;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesFondo
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesFondo()
    {
        return $this->aprobarPrespuestoCentroCostoMesFondo;
    }

    /**
     * Set totalSalarioDivision
     *
     * @param integer $totalSalarioDivision
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalSalarioDivision($totalSalarioDivision)
    {
        $this->totalSalarioDivision = $totalSalarioDivision;

        return $this;
    }

    /**
     * Get totalSalarioDivision
     *
     * @return integer
     */
    public function getTotalSalarioDivision()
    {
        return $this->totalSalarioDivision;
    }

    /**
     * Set totalGastoSalarioPesoProduccion
     *
     * @param integer $totalGastoSalarioPesoProduccion
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalGastoSalarioPesoProduccion($totalGastoSalarioPesoProduccion)
    {
        $this->totalGastoSalarioPesoProduccion = $totalGastoSalarioPesoProduccion;

        return $this;
    }

    /**
     * Get totalGastoSalarioPesoProduccion
     *
     * @return integer
     */
    public function getTotalGastoSalarioPesoProduccion()
    {
        return $this->totalGastoSalarioPesoProduccion;
    }

    /**
     * Set totalSalarioMedio
     *
     * @param integer $totalSalarioMedio
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalSalarioMedio($totalSalarioMedio)
    {
        $this->totalSalarioMedio = $totalSalarioMedio;

        return $this;
    }

    /**
     * Get totalSalarioMedio
     *
     * @return integer
     */
    public function getTotalSalarioMedio()
    {
        return $this->totalSalarioMedio;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoDivisionSalario
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
     * @return PlanEstimadoDivisionSalario
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

    /**
     * Set totalPromedioTrabajador
     *
     * @param integer $totalPromedioTrabajador
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalPromedioTrabajador($totalPromedioTrabajador)
    {
        $this->totalPromedioTrabajador = $totalPromedioTrabajador;

        return $this;
    }

    /**
     * Get totalPromedioTrabajador
     *
     * @return integer
     */
    public function getTotalPromedioTrabajador()
    {
        return $this->totalPromedioTrabajador;
    }

    /**
     * Set totalSalarioDirecto
     *
     * @param string $totalSalarioDirecto
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalSalarioDirecto($totalSalarioDirecto)
    {
        $this->totalSalarioDirecto = $totalSalarioDirecto;

        return $this;
    }

    /**
     * Get totalSalarioDirecto
     *
     * @return string
     */
    public function getTotalSalarioDirecto()
    {
        return $this->totalSalarioDirecto;
    }

    /**
     * Set totalSalarioIndirecto
     *
     * @param string $totalSalarioIndirecto
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalSalarioIndirecto($totalSalarioIndirecto)
    {
        $this->totalSalarioIndirecto = $totalSalarioIndirecto;

        return $this;
    }

    /**
     * Get totalSalarioIndirecto
     *
     * @return string
     */
    public function getTotalSalarioIndirecto()
    {
        return $this->totalSalarioIndirecto;
    }

    /**
     * Set totalSalarioAdministrativo
     *
     * @param string $totalSalarioAdministrativo
     *
     * @return PlanEstimadoDivisionSalario
     */
    public function setTotalSalarioAdministrativo($totalSalarioAdministrativo)
    {
        $this->totalSalarioAdministrativo = $totalSalarioAdministrativo;

        return $this;
    }

    /**
     * Get totalSalarioAdministrativo
     *
     * @return string
     */
    public function getTotalSalarioAdministrativo()
    {
        return $this->totalSalarioAdministrativo;
    }
}
