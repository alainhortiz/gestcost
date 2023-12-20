<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoCentroCostoMesSalario
 *
 * @ORM\Table(name="plan_estimado_centro_costo_mes_salario",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA08", columns={"mes","centro_costo_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoCentroCostoMesSalarioRepository")
 */
class PlanEstimadoCentroCostoMesSalario
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
     * @ORM\Column(name="totalSalarioCentroCostoMes", type="integer",nullable=true)
     */
    private $totalSalarioCentroCostoMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPromedioTrabajadorCentroCostoMes", type="integer",nullable=true)
     */
    private $totalPromedioTrabajadorCentroCostoMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalGastoSalarioPesoProduccionCentroCostoMes", type="integer",nullable=true)
     */
    private $totalGastoSalarioPesoProduccionCentroCostoMes = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalSalarioMedioCentroCostoMes", type="integer",nullable=true)
     */
    private $totalSalarioMedioCentroCostoMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioDirectoCentroCostoMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioDirectoCentroCostoMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioIndirectoCentroCostoMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioIndirectoCentroCostoMes = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="totalSalarioAdministrativoCentroCostoMes", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalSalarioAdministrativoCentroCostoMes = 0;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="planEstimadoCentrosCostosMeses")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="CentroCosto",inversedBy="planEstimadoCentrosCostosMeses")
     */
    protected $centroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoCentrosCostosRecursosHumanosMeses")
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
     * @return PlanEstimadoCentroCostoMesSalario
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
     * Set totalSalarioCentroCostoMes
     *
     * @param integer $totalSalarioCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalSalarioCentroCostoMes($totalSalarioCentroCostoMes)
    {
        $this->totalSalarioCentroCostoMes = $totalSalarioCentroCostoMes;

        return $this;
    }

    /**
     * Get totalSalarioCentroCostoMes
     *
     * @return integer
     */
    public function getTotalSalarioCentroCostoMes()
    {
        return $this->totalSalarioCentroCostoMes;
    }

    /**
     * Set totalGastoSalarioPesoProduccionCentroCostoMes
     *
     * @param integer $totalGastoSalarioPesoProduccionCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalGastoSalarioPesoProduccionCentroCostoMes($totalGastoSalarioPesoProduccionCentroCostoMes)
    {
        $this->totalGastoSalarioPesoProduccionCentroCostoMes = $totalGastoSalarioPesoProduccionCentroCostoMes;

        return $this;
    }

    /**
     * Get totalGastoSalarioPesoProduccionCentroCostoMes
     *
     * @return integer
     */
    public function getTotalGastoSalarioPesoProduccionCentroCostoMes()
    {
        return $this->totalGastoSalarioPesoProduccionCentroCostoMes;
    }

    /**
     * Set totalSalarioMedioCentroCostoMes
     *
     * @param integer $totalSalarioMedioCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalSalarioMedioCentroCostoMes($totalSalarioMedioCentroCostoMes)
    {
        $this->totalSalarioMedioCentroCostoMes = $totalSalarioMedioCentroCostoMes;

        return $this;
    }

    /**
     * Get totalSalarioMedioCentroCostoMes
     *
     * @return integer
     */
    public function getTotalSalarioMedioCentroCostoMes()
    {
        return $this->totalSalarioMedioCentroCostoMes;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return PlanEstimadoCentroCostoMesSalario
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
     * Set centroCosto
     *
     * @param \AppBundle\Entity\CentroCosto $centroCosto
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setCentroCosto(\AppBundle\Entity\CentroCosto $centroCosto = null)
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

    /**
     * Get centroCosto
     *
     * @return \AppBundle\Entity\CentroCosto
     */
    public function getCentroCosto()
    {
        return $this->centroCosto;
    }

    /**
     * Set planEstimadoIndicadores
     *
     * @param \AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores
     *
     * @return PlanEstimadoCentroCostoMesSalario
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
     * Set totalPromedioTrabajadorCentroCostoMes
     *
     * @param integer $totalPromedioTrabajadorCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalPromedioTrabajadorCentroCostoMes($totalPromedioTrabajadorCentroCostoMes)
    {
        $this->totalPromedioTrabajadorCentroCostoMes = $totalPromedioTrabajadorCentroCostoMes;

        return $this;
    }

    /**
     * Get totalPromedioTrabajadorCentroCostoMes
     *
     * @return integer
     */
    public function getTotalPromedioTrabajadorCentroCostoMes()
    {
        return $this->totalPromedioTrabajadorCentroCostoMes;
    }

    /**
     * Set totalSalarioDirectoCentroCostoMes
     *
     * @param string $totalSalarioDirectoCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalSalarioDirectoCentroCostoMes($totalSalarioDirectoCentroCostoMes)
    {
        $this->totalSalarioDirectoCentroCostoMes = $totalSalarioDirectoCentroCostoMes;

        return $this;
    }

    /**
     * Get totalSalarioDirectoCentroCostoMes
     *
     * @return string
     */
    public function getTotalSalarioDirectoCentroCostoMes()
    {
        return $this->totalSalarioDirectoCentroCostoMes;
    }

    /**
     * Set totalSalarioIndirectoCentroCostoMes
     *
     * @param string $totalSalarioIndirectoCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalSalarioIndirectoCentroCostoMes($totalSalarioIndirectoCentroCostoMes)
    {
        $this->totalSalarioIndirectoCentroCostoMes = $totalSalarioIndirectoCentroCostoMes;

        return $this;
    }

    /**
     * Get totalSalarioIndirectoCentroCostoMes
     *
     * @return string
     */
    public function getTotalSalarioIndirectoCentroCostoMes()
    {
        return $this->totalSalarioIndirectoCentroCostoMes;
    }

    /**
     * Set totalSalarioAdministrativoCentroCostoMes
     *
     * @param string $totalSalarioAdministrativoCentroCostoMes
     *
     * @return PlanEstimadoCentroCostoMesSalario
     */
    public function setTotalSalarioAdministrativoCentroCostoMes($totalSalarioAdministrativoCentroCostoMes)
    {
        $this->totalSalarioAdministrativoCentroCostoMes = $totalSalarioAdministrativoCentroCostoMes;

        return $this;
    }

    /**
     * Get totalSalarioAdministrativoCentroCostoMes
     *
     * @return string
     */
    public function getTotalSalarioAdministrativoCentroCostoMes()
    {
        return $this->totalSalarioAdministrativoCentroCostoMes;
    }
}
