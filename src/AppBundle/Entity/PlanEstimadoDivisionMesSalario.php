<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanEstimadoDivisionMesSalario
 *
 * @ORM\Table(name="plan_estimado_division_mes_salario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoDivisionMesSalarioRepository")
 */
class PlanEstimadoDivisionMesSalario
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
     * @var string
     *
     * @ORM\Column(name="mes", type="string", length=10)
     */
    private $mes;

    /**
     * @var int
     *
     * @ORM\Column(name="totalSalarioDivisionMes", type="integer",nullable=true)
     */
    private $totalSalarioDivisionMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPromedioTrabajadorMes", type="integer",nullable=true)
     */
    private $totalPromedioTrabajadorMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalGastoSalarioPesoProduccionDivisionMes", type="integer",nullable=true)
     */
    private $totalGastoSalarioPesoProduccionDivisionMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalSalarioMedioDivisionMes", type="integer",nullable=true)
     */
    private $totalSalarioMedioDivisionMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioDirectoDivisionMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioDirectoDivisionMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioIndirectoDivisionMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioIndirectoDivisionMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioAdministrativoDivisionMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioAdministrativoDivisionMes = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoRecursosHumanosMeses")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoRecursosHumanosMeses")
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
     * Set mes
     *
     * @param string $mes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set totalSalarioDivisionMes
     *
     * @param integer $totalSalarioDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalSalarioDivisionMes($totalSalarioDivisionMes)
    {
        $this->totalSalarioDivisionMes = $totalSalarioDivisionMes;

        return $this;
    }

    /**
     * Get totalSalarioDivisionMes
     *
     * @return integer
     */
    public function getTotalSalarioDivisionMes()
    {
        return $this->totalSalarioDivisionMes;
    }

    /**
     * Set totalGastoSalarioPesoProduccionDivisionMes
     *
     * @param integer $totalGastoSalarioPesoProduccionDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalGastoSalarioPesoProduccionDivisionMes($totalGastoSalarioPesoProduccionDivisionMes)
    {
        $this->totalGastoSalarioPesoProduccionDivisionMes = $totalGastoSalarioPesoProduccionDivisionMes;

        return $this;
    }

    /**
     * Get totalGastoSalarioPesoProduccionDivisionMes
     *
     * @return integer
     */
    public function getTotalGastoSalarioPesoProduccionDivisionMes()
    {
        return $this->totalGastoSalarioPesoProduccionDivisionMes;
    }

    /**
     * Set totalSalarioMedioDivisionMes
     *
     * @param integer $totalSalarioMedioDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalSalarioMedioDivisionMes($totalSalarioMedioDivisionMes)
    {
        $this->totalSalarioMedioDivisionMes = $totalSalarioMedioDivisionMes;

        return $this;
    }

    /**
     * Get totalSalarioMedioDivisionMes
     *
     * @return integer
     */
    public function getTotalSalarioMedioDivisionMes()
    {
        return $this->totalSalarioMedioDivisionMes;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoDivisionMesSalario
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
     * @return PlanEstimadoDivisionMesSalario
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
     * Set totalPromedioTrabajadorMes
     *
     * @param integer $totalPromedioTrabajadorMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalPromedioTrabajadorMes($totalPromedioTrabajadorMes)
    {
        $this->totalPromedioTrabajadorMes = $totalPromedioTrabajadorMes;

        return $this;
    }

    /**
     * Get totalPromedioTrabajadorMes
     *
     * @return integer
     */
    public function getTotalPromedioTrabajadorMes()
    {
        return $this->totalPromedioTrabajadorMes;
    }

    /**
     * Set totalSalarioDirectoDivisionMes
     *
     * @param string $totalSalarioDirectoDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalSalarioDirectoDivisionMes($totalSalarioDirectoDivisionMes)
    {
        $this->totalSalarioDirectoDivisionMes = $totalSalarioDirectoDivisionMes;

        return $this;
    }

    /**
     * Get totalSalarioDirectoDivisionMes
     *
     * @return string
     */
    public function getTotalSalarioDirectoDivisionMes()
    {
        return $this->totalSalarioDirectoDivisionMes;
    }

    /**
     * Set totalSalarioIndirectoDivisionMes
     *
     * @param string $totalSalarioIndirectoDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalSalarioIndirectoDivisionMes($totalSalarioIndirectoDivisionMes)
    {
        $this->totalSalarioIndirectoDivisionMes = $totalSalarioIndirectoDivisionMes;

        return $this;
    }

    /**
     * Get totalSalarioIndirectoDivisionMes
     *
     * @return string
     */
    public function getTotalSalarioIndirectoDivisionMes()
    {
        return $this->totalSalarioIndirectoDivisionMes;
    }

    /**
     * Set totalSalarioAdministrativoDivisionMes
     *
     * @param string $totalSalarioAdministrativoDivisionMes
     *
     * @return PlanEstimadoDivisionMesSalario
     */
    public function setTotalSalarioAdministrativoDivisionMes($totalSalarioAdministrativoDivisionMes)
    {
        $this->totalSalarioAdministrativoDivisionMes = $totalSalarioAdministrativoDivisionMes;

        return $this;
    }

    /**
     * Get totalSalarioAdministrativoDivisionMes
     *
     * @return string
     */
    public function getTotalSalarioAdministrativoDivisionMes()
    {
        return $this->totalSalarioAdministrativoDivisionMes;
    }
}
