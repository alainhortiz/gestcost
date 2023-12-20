<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlanEstimadoIndicadores
 *
 * @ORM\Table(name="plan_estimado_indicadores")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoIndicadoresRepository")
 */
class PlanEstimadoIndicadores
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
     * @var int
     *
     * @ORM\Column(name="year", type="integer", unique=true)
     */
    private $year;

    /**
     * @var bool
     *
     * @ORM\Column(name="inicio", type="boolean")
     */
    private $inicio = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobado", type="boolean")
     */
    private $aprobado = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="planReal", type="boolean")
     */
    private $planReal = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalVenta", type="boolean")
     */
    private $aprobarPrespuestoTotalVenta = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionVenta", type="boolean")
     */
    private $aprobarPrespuestoDivisionVenta = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMesVenta", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesVenta = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesVenta", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesVenta = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalRecursosHumanos", type="boolean")
     */
    private $aprobarPrespuestoTotalRecursosHumanos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionRecursosHumanos", type="boolean")
     */
    private $aprobarPrespuestoDivisionRecursosHumanos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMesRecursosHumanos", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesRecursosHumanos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesRecursosHumanos", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesRecursosHumanos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalOtrosGastos", type="boolean")
     */
    private $aprobarPrespuestoTotalOtrosGastos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoOtrosGastos", type="boolean")
     */
    private $aprobarPrespuestoOtrosGastos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoMesOtrosGastos", type="boolean")
     */
    private $aprobarPrespuestoMesOtrosGastos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMesOtrosGastos", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesOtrosGastos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesOtrosGastos", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesOtrosGastos = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalCombustible", type="boolean")
     */
    private $aprobarPrespuestoTotalCombustible = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalLubricante", type="boolean")
     */
    private $aprobarPrespuestoTotalLubricante = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalEnergia", type="boolean")
     */
    private $aprobarPrespuestoTotalEnergia = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobadoEstimadoCombustibleMedioTransporte", type="boolean")
     */
    private $aprobadoEstimadoCombustibleMedioTransporte = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobadoEstimadoLubricanteMedioTransporte", type="boolean")
     */
    private $aprobadoEstimadoLubricanteMedioTransporte = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionEnergia", type="boolean")
     */
    private $aprobarPrespuestoDivisionEnergia = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMesEnergia", type="boolean")
     */
    private $aprobarPrespuestoDivisionMesEnergia = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMesEnergia", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMesEnergia = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionMateriaPrima", type="boolean")
     */
    private $aprobarPrespuestoDivisionMateriaPrima = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoMateriaPrima", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoMateriaPrima = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalMateriaPrima", type="boolean")
     */
    private $aprobarPrespuestoTotalMateriaPrima = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionDepreciacion", type="boolean")
     */
    private $aprobarPrespuestoDivisionDepreciacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoDepreciacion", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoDepreciacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalDepreciacion", type="boolean")
     */
    private $aprobarPrespuestoTotalDepreciacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionBonificacion", type="boolean")
     */
    private $aprobarPrespuestoDivisionBonificacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoBonificacion", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoBonificacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalBonificacion", type="boolean")
     */
    private $aprobarPrespuestoTotalBonificacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionComedor", type="boolean")
     */
    private $aprobarPrespuestoDivisionComedor = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoComedor", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoComedor = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalComedor", type="boolean")
     */
    private $aprobarPrespuestoTotalComedor = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionAmortizacion", type="boolean")
     */
    private $aprobarPrespuestoDivisionAmortizacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoAmortizacion", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoAmortizacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalAmortizacion", type="boolean")
     */
    private $aprobarPrespuestoTotalAmortizacion = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoDivisionOtroIngreso", type="boolean")
     */
    private $aprobarPrespuestoDivisionOtroIngreso = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoCentroCostoOtroIngreso", type="boolean")
     */
    private $aprobarPrespuestoCentroCostoOtroIngreso = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobarPrespuestoTotalOtroIngreso", type="boolean")
     */
    private $aprobarPrespuestoTotalOtroIngreso = false;

    /**
     * @var int
     *
     * @ORM\Column(name="totalVenta", type="integer",nullable=true)
     */
    private $totalVenta = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalFondoSalario", type="integer",nullable=true)
     */
    private $totalFondoSalario = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalOtrosGastos", type="integer",nullable=true)
     */
    private $totalOtrosGastos = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalLtsCombustible", type="integer",nullable=true)
     */
    private $totalLtsCombustible = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalCombustible",  type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalCombustible = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalLtsLubricante", type="integer",nullable=true)
     */
    private $totalLtsLubricante = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalLubricante",  type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalLubricante = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalEnergiaPresupuesto",  type="decimal", precision=18, scale=2, nullable=true)
     */
    private $totalEnergiaPresupuesto = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="precioEnergia", type="decimal", precision=18, scale=4, nullable=false)
     */
    private $precioEnergia = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalEnergiaKW", type="integer",nullable=true)
     */
    private $totalEnergiaKW = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalMateriaPrima", type="integer",nullable=true)
     */
    private $totalMateriaPrima = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalDepreciacion", type="integer",nullable=true)
     */
    private $totalDepreciacion = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalBonificacion", type="integer",nullable=true)
     */
    private $totalBonificacion = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalComedor", type="integer",nullable=true)
     */
    private $totalComedor = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalAmortizacion", type="integer",nullable=true)
     */
    private $totalAmortizacion = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="totalOtroIngreso", type="integer",nullable=true)
     */
    private $totalOtroIngreso = 0;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoDivision", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoDivisiones;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoDivisionSalario", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoRecursosHumanos;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoDivisionMes", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoDivisionesMeses;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoDivisionMesSalario", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoRecursosHumanosMeses;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoCentroCostoMes", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoCentrosCostosMeses;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoCentroCostoMesSalario", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadoCentrosCostosRecursosHumanosMeses;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoOtrosGastos", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadosOtrosGastos;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoMesOtrosGastos", mappedBy="planEstimadoIndicadores")
     */
    private $planEstimadosMesOtrosGastos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->planEstimadoDivisiones = new ArrayCollection();
        $this->planEstimadoDivisionesMeses = new ArrayCollection();
        $this->planEstimadoCentrosCostosMeses = new ArrayCollection();
        $this->planEstimadoRecursosHumanos = new ArrayCollection();
        $this->planEstimadoRecursosHumanosMeses = new ArrayCollection();
        $this->planEstimadoCentrosCostosRecursosHumanosMeses = new ArrayCollection();
        $this->planEstimadosOtrosGastos = new ArrayCollection();
        $this->planEstimadosMesOtrosGastos = new ArrayCollection();
    }

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
     * Set year
     *
     * @param integer $year
     *
     * @return PlanEstimadoIndicadores
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set inicio
     *
     * @param boolean $inicio
     *
     * @return PlanEstimadoIndicadores
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return boolean
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set aprobado
     *
     * @param boolean $aprobado
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobado($aprobado)
    {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado
     *
     * @return boolean
     */
    public function getAprobado()
    {
        return $this->aprobado;
    }

    /**
     * Set aprobarPrespuestoTotalVenta
     *
     * @param boolean $aprobarPrespuestoTotalVenta
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalVenta($aprobarPrespuestoTotalVenta)
    {
        $this->aprobarPrespuestoTotalVenta = $aprobarPrespuestoTotalVenta;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalVenta
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalVenta()
    {
        return $this->aprobarPrespuestoTotalVenta;
    }

    /**
     * Set aprobarPrespuestoDivisionVenta
     *
     * @param boolean $aprobarPrespuestoDivisionVenta
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionVenta($aprobarPrespuestoDivisionVenta)
    {
        $this->aprobarPrespuestoDivisionVenta = $aprobarPrespuestoDivisionVenta;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionVenta
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionVenta()
    {
        return $this->aprobarPrespuestoDivisionVenta;
    }

    /**
     * Set aprobarPrespuestoDivisionMesVenta
     *
     * @param boolean $aprobarPrespuestoDivisionMesVenta
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionMesVenta($aprobarPrespuestoDivisionMesVenta)
    {
        $this->aprobarPrespuestoDivisionMesVenta = $aprobarPrespuestoDivisionMesVenta;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesVenta
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesVenta()
    {
        return $this->aprobarPrespuestoDivisionMesVenta;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesVenta
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesVenta
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoMesVenta($aprobarPrespuestoCentroCostoMesVenta)
    {
        $this->aprobarPrespuestoCentroCostoMesVenta = $aprobarPrespuestoCentroCostoMesVenta;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesVenta
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesVenta()
    {
        return $this->aprobarPrespuestoCentroCostoMesVenta;
    }

    /**
     * Set aprobarPrespuestoTotalRecursosHumanos
     *
     * @param boolean $aprobarPrespuestoTotalRecursosHumanos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalRecursosHumanos($aprobarPrespuestoTotalRecursosHumanos)
    {
        $this->aprobarPrespuestoTotalRecursosHumanos = $aprobarPrespuestoTotalRecursosHumanos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalRecursosHumanos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalRecursosHumanos()
    {
        return $this->aprobarPrespuestoTotalRecursosHumanos;
    }

    /**
     * Set aprobarPrespuestoDivisionRecursosHumanos
     *
     * @param boolean $aprobarPrespuestoDivisionRecursosHumanos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionRecursosHumanos($aprobarPrespuestoDivisionRecursosHumanos)
    {
        $this->aprobarPrespuestoDivisionRecursosHumanos = $aprobarPrespuestoDivisionRecursosHumanos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionRecursosHumanos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionRecursosHumanos()
    {
        return $this->aprobarPrespuestoDivisionRecursosHumanos;
    }

    /**
     * Set aprobarPrespuestoDivisionMesRecursosHumanos
     *
     * @param boolean $aprobarPrespuestoDivisionMesRecursosHumanos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionMesRecursosHumanos($aprobarPrespuestoDivisionMesRecursosHumanos)
    {
        $this->aprobarPrespuestoDivisionMesRecursosHumanos = $aprobarPrespuestoDivisionMesRecursosHumanos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesRecursosHumanos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesRecursosHumanos()
    {
        return $this->aprobarPrespuestoDivisionMesRecursosHumanos;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesRecursosHumanos
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesRecursosHumanos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoMesRecursosHumanos($aprobarPrespuestoCentroCostoMesRecursosHumanos)
    {
        $this->aprobarPrespuestoCentroCostoMesRecursosHumanos = $aprobarPrespuestoCentroCostoMesRecursosHumanos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesRecursosHumanos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesRecursosHumanos()
    {
        return $this->aprobarPrespuestoCentroCostoMesRecursosHumanos;
    }

    /**
     * Set aprobarPrespuestoTotalOtrosGastos
     *
     * @param boolean $aprobarPrespuestoTotalOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalOtrosGastos($aprobarPrespuestoTotalOtrosGastos)
    {
        $this->aprobarPrespuestoTotalOtrosGastos = $aprobarPrespuestoTotalOtrosGastos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalOtrosGastos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalOtrosGastos()
    {
        return $this->aprobarPrespuestoTotalOtrosGastos;
    }

    /**
     * Set aprobarPrespuestoOtrosGastos
     *
     * @param boolean $aprobarPrespuestoOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoOtrosGastos($aprobarPrespuestoOtrosGastos)
    {
        $this->aprobarPrespuestoOtrosGastos = $aprobarPrespuestoOtrosGastos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoOtrosGastos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoOtrosGastos()
    {
        return $this->aprobarPrespuestoOtrosGastos;
    }

    /**
     * Set aprobarPrespuestoMesOtrosGastos
     *
     * @param boolean $aprobarPrespuestoMesOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoMesOtrosGastos($aprobarPrespuestoMesOtrosGastos)
    {
        $this->aprobarPrespuestoMesOtrosGastos = $aprobarPrespuestoMesOtrosGastos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoMesOtrosGastos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoMesOtrosGastos()
    {
        return $this->aprobarPrespuestoMesOtrosGastos;
    }

    /**
     * Set aprobarPrespuestoDivisionMesOtrosGastos
     *
     * @param boolean $aprobarPrespuestoDivisionMesOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionMesOtrosGastos($aprobarPrespuestoDivisionMesOtrosGastos)
    {
        $this->aprobarPrespuestoDivisionMesOtrosGastos = $aprobarPrespuestoDivisionMesOtrosGastos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesOtrosGastos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesOtrosGastos()
    {
        return $this->aprobarPrespuestoDivisionMesOtrosGastos;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesOtrosGastos
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoMesOtrosGastos($aprobarPrespuestoCentroCostoMesOtrosGastos)
    {
        $this->aprobarPrespuestoCentroCostoMesOtrosGastos = $aprobarPrespuestoCentroCostoMesOtrosGastos;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesOtrosGastos
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesOtrosGastos()
    {
        return $this->aprobarPrespuestoCentroCostoMesOtrosGastos;
    }

    /**
     * Set aprobarPrespuestoTotalCombustible
     *
     * @param boolean $aprobarPrespuestoTotalCombustible
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalCombustible($aprobarPrespuestoTotalCombustible)
    {
        $this->aprobarPrespuestoTotalCombustible = $aprobarPrespuestoTotalCombustible;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalCombustible
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalCombustible()
    {
        return $this->aprobarPrespuestoTotalCombustible;
    }

    /**
     * Set aprobarPrespuestoTotalLubricante
     *
     * @param boolean $aprobarPrespuestoTotalLubricante
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalLubricante($aprobarPrespuestoTotalLubricante)
    {
        $this->aprobarPrespuestoTotalLubricante = $aprobarPrespuestoTotalLubricante;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalLubricante
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalLubricante()
    {
        return $this->aprobarPrespuestoTotalLubricante;
    }

    /**
     * Set aprobarPrespuestoTotalEnergia
     *
     * @param boolean $aprobarPrespuestoTotalEnergia
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalEnergia($aprobarPrespuestoTotalEnergia)
    {
        $this->aprobarPrespuestoTotalEnergia = $aprobarPrespuestoTotalEnergia;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalEnergia
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalEnergia()
    {
        return $this->aprobarPrespuestoTotalEnergia;
    }

    /**
     * Set aprobadoEstimadoCombustibleMedioTransporte
     *
     * @param boolean $aprobadoEstimadoCombustibleMedioTransporte
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobadoEstimadoCombustibleMedioTransporte($aprobadoEstimadoCombustibleMedioTransporte)
    {
        $this->aprobadoEstimadoCombustibleMedioTransporte = $aprobadoEstimadoCombustibleMedioTransporte;

        return $this;
    }

    /**
     * Get aprobadoEstimadoCombustibleMedioTransporte
     *
     * @return boolean
     */
    public function getAprobadoEstimadoCombustibleMedioTransporte()
    {
        return $this->aprobadoEstimadoCombustibleMedioTransporte;
    }

    /**
     * Set aprobadoEstimadoLubricanteMedioTransporte
     *
     * @param boolean $aprobadoEstimadoLubricanteMedioTransporte
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobadoEstimadoLubricanteMedioTransporte($aprobadoEstimadoLubricanteMedioTransporte)
    {
        $this->aprobadoEstimadoLubricanteMedioTransporte = $aprobadoEstimadoLubricanteMedioTransporte;

        return $this;
    }

    /**
     * Get aprobadoEstimadoLubricanteMedioTransporte
     *
     * @return boolean
     */
    public function getAprobadoEstimadoLubricanteMedioTransporte()
    {
        return $this->aprobadoEstimadoLubricanteMedioTransporte;
    }

    /**
     * Set aprobarPrespuestoDivisionEnergia
     *
     * @param boolean $aprobarPrespuestoDivisionEnergia
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionEnergia($aprobarPrespuestoDivisionEnergia)
    {
        $this->aprobarPrespuestoDivisionEnergia = $aprobarPrespuestoDivisionEnergia;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionEnergia
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionEnergia()
    {
        return $this->aprobarPrespuestoDivisionEnergia;
    }

    /**
     * Set aprobarPrespuestoDivisionMesEnergia
     *
     * @param boolean $aprobarPrespuestoDivisionMesEnergia
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionMesEnergia($aprobarPrespuestoDivisionMesEnergia)
    {
        $this->aprobarPrespuestoDivisionMesEnergia = $aprobarPrespuestoDivisionMesEnergia;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMesEnergia
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMesEnergia()
    {
        return $this->aprobarPrespuestoDivisionMesEnergia;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMesEnergia
     *
     * @param boolean $aprobarPrespuestoCentroCostoMesEnergia
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoMesEnergia($aprobarPrespuestoCentroCostoMesEnergia)
    {
        $this->aprobarPrespuestoCentroCostoMesEnergia = $aprobarPrespuestoCentroCostoMesEnergia;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMesEnergia
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMesEnergia()
    {
        return $this->aprobarPrespuestoCentroCostoMesEnergia;
    }

    /**
     * Set aprobarPrespuestoDivisionMateriaPrima
     *
     * @param boolean $aprobarPrespuestoDivisionMateriaPrima
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionMateriaPrima($aprobarPrespuestoDivisionMateriaPrima)
    {
        $this->aprobarPrespuestoDivisionMateriaPrima = $aprobarPrespuestoDivisionMateriaPrima;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionMateriaPrima
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionMateriaPrima()
    {
        return $this->aprobarPrespuestoDivisionMateriaPrima;
    }

    /**
     * Set aprobarPrespuestoCentroCostoMateriaPrima
     *
     * @param boolean $aprobarPrespuestoCentroCostoMateriaPrima
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoMateriaPrima($aprobarPrespuestoCentroCostoMateriaPrima)
    {
        $this->aprobarPrespuestoCentroCostoMateriaPrima = $aprobarPrespuestoCentroCostoMateriaPrima;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoMateriaPrima
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoMateriaPrima()
    {
        return $this->aprobarPrespuestoCentroCostoMateriaPrima;
    }

    /**
     * Set aprobarPrespuestoTotalMateriaPrima
     *
     * @param boolean $aprobarPrespuestoTotalMateriaPrima
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalMateriaPrima($aprobarPrespuestoTotalMateriaPrima)
    {
        $this->aprobarPrespuestoTotalMateriaPrima = $aprobarPrespuestoTotalMateriaPrima;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalMateriaPrima
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalMateriaPrima()
    {
        return $this->aprobarPrespuestoTotalMateriaPrima;
    }

    /**
     * Set aprobarPrespuestoDivisionDepreciacion
     *
     * @param boolean $aprobarPrespuestoDivisionDepreciacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionDepreciacion($aprobarPrespuestoDivisionDepreciacion)
    {
        $this->aprobarPrespuestoDivisionDepreciacion = $aprobarPrespuestoDivisionDepreciacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionDepreciacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionDepreciacion()
    {
        return $this->aprobarPrespuestoDivisionDepreciacion;
    }

    /**
     * Set aprobarPrespuestoCentroCostoDepreciacion
     *
     * @param boolean $aprobarPrespuestoCentroCostoDepreciacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoDepreciacion($aprobarPrespuestoCentroCostoDepreciacion)
    {
        $this->aprobarPrespuestoCentroCostoDepreciacion = $aprobarPrespuestoCentroCostoDepreciacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoDepreciacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoDepreciacion()
    {
        return $this->aprobarPrespuestoCentroCostoDepreciacion;
    }

    /**
     * Set aprobarPrespuestoTotalDepreciacion
     *
     * @param boolean $aprobarPrespuestoTotalDepreciacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalDepreciacion($aprobarPrespuestoTotalDepreciacion)
    {
        $this->aprobarPrespuestoTotalDepreciacion = $aprobarPrespuestoTotalDepreciacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalDepreciacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalDepreciacion()
    {
        return $this->aprobarPrespuestoTotalDepreciacion;
    }

    /**
     * Set aprobarPrespuestoDivisionAmortizacion
     *
     * @param boolean $aprobarPrespuestoDivisionAmortizacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionAmortizacion($aprobarPrespuestoDivisionAmortizacion)
    {
        $this->aprobarPrespuestoDivisionAmortizacion = $aprobarPrespuestoDivisionAmortizacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionAmortizacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionAmortizacion()
    {
        return $this->aprobarPrespuestoDivisionAmortizacion;
    }

    /**
     * Set aprobarPrespuestoCentroCostoAmortizacion
     *
     * @param boolean $aprobarPrespuestoCentroCostoAmortizacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoAmortizacion($aprobarPrespuestoCentroCostoAmortizacion)
    {
        $this->aprobarPrespuestoCentroCostoAmortizacion = $aprobarPrespuestoCentroCostoAmortizacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoAmortizacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoAmortizacion()
    {
        return $this->aprobarPrespuestoCentroCostoAmortizacion;
    }

    /**
     * Set aprobarPrespuestoTotalAmortizacion
     *
     * @param boolean $aprobarPrespuestoTotalAmortizacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalAmortizacion($aprobarPrespuestoTotalAmortizacion)
    {
        $this->aprobarPrespuestoTotalAmortizacion = $aprobarPrespuestoTotalAmortizacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalAmortizacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalAmortizacion()
    {
        return $this->aprobarPrespuestoTotalAmortizacion;
    }

    /**
     * Set aprobarPrespuestoDivisionOtroIngreso
     *
     * @param boolean $aprobarPrespuestoDivisionOtroIngreso
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionOtroIngreso($aprobarPrespuestoDivisionOtroIngreso)
    {
        $this->aprobarPrespuestoDivisionOtroIngreso = $aprobarPrespuestoDivisionOtroIngreso;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionOtroIngreso
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionOtroIngreso()
    {
        return $this->aprobarPrespuestoDivisionOtroIngreso;
    }

    /**
     * Set aprobarPrespuestoCentroCostoOtroIngreso
     *
     * @param boolean $aprobarPrespuestoCentroCostoOtroIngreso
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoOtroIngreso($aprobarPrespuestoCentroCostoOtroIngreso)
    {
        $this->aprobarPrespuestoCentroCostoOtroIngreso = $aprobarPrespuestoCentroCostoOtroIngreso;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoOtroIngreso
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoOtroIngreso()
    {
        return $this->aprobarPrespuestoCentroCostoOtroIngreso;
    }

    /**
     * Set aprobarPrespuestoTotalOtroIngreso
     *
     * @param boolean $aprobarPrespuestoTotalOtroIngreso
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalOtroIngreso($aprobarPrespuestoTotalOtroIngreso)
    {
        $this->aprobarPrespuestoTotalOtroIngreso = $aprobarPrespuestoTotalOtroIngreso;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalOtroIngreso
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalOtroIngreso()
    {
        return $this->aprobarPrespuestoTotalOtroIngreso;
    }

    /**
     * Set totalVenta
     *
     * @param integer $totalVenta
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalVenta($totalVenta)
    {
        $this->totalVenta = $totalVenta;

        return $this;
    }

    /**
     * Get totalVenta
     *
     * @return integer
     */
    public function getTotalVenta()
    {
        return $this->totalVenta;
    }

    /**
     * Set totalFondoSalario
     *
     * @param integer $totalFondoSalario
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalFondoSalario($totalFondoSalario)
    {
        $this->totalFondoSalario = $totalFondoSalario;

        return $this;
    }

    /**
     * Get totalFondoSalario
     *
     * @return integer
     */
    public function getTotalFondoSalario()
    {
        return $this->totalFondoSalario;
    }

    /**
     * Set totalOtrosGastos
     *
     * @param integer $totalOtrosGastos
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalOtrosGastos($totalOtrosGastos)
    {
        $this->totalOtrosGastos = $totalOtrosGastos;

        return $this;
    }

    /**
     * Get totalOtrosGastos
     *
     * @return integer
     */
    public function getTotalOtrosGastos()
    {
        return $this->totalOtrosGastos;
    }

    /**
     * Set totalLtsCombustible
     *
     * @param integer $totalLtsCombustible
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalLtsCombustible($totalLtsCombustible)
    {
        $this->totalLtsCombustible = $totalLtsCombustible;

        return $this;
    }

    /**
     * Get totalLtsCombustible
     *
     * @return integer
     */
    public function getTotalLtsCombustible()
    {
        return $this->totalLtsCombustible;
    }

    /**
     * Set totalCombustible
     *
     * @param string $totalCombustible
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalCombustible($totalCombustible)
    {
        $this->totalCombustible = $totalCombustible;

        return $this;
    }

    /**
     * Get totalCombustible
     *
     * @return string
     */
    public function getTotalCombustible()
    {
        return $this->totalCombustible;
    }

    /**
     * Set totalLtsLubricante
     *
     * @param integer $totalLtsLubricante
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalLtsLubricante($totalLtsLubricante)
    {
        $this->totalLtsLubricante = $totalLtsLubricante;

        return $this;
    }

    /**
     * Get totalLtsLubricante
     *
     * @return integer
     */
    public function getTotalLtsLubricante()
    {
        return $this->totalLtsLubricante;
    }

    /**
     * Set totalLubricante
     *
     * @param string $totalLubricante
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalLubricante($totalLubricante)
    {
        $this->totalLubricante = $totalLubricante;

        return $this;
    }

    /**
     * Get totalLubricante
     *
     * @return string
     */
    public function getTotalLubricante()
    {
        return $this->totalLubricante;
    }

    /**
     * Set totalEnergiaPresupuesto
     *
     * @param string $totalEnergiaPresupuesto
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalEnergiaPresupuesto($totalEnergiaPresupuesto)
    {
        $this->totalEnergiaPresupuesto = $totalEnergiaPresupuesto;

        return $this;
    }

    /**
     * Get totalEnergiaPresupuesto
     *
     * @return string
     */
    public function getTotalEnergiaPresupuesto()
    {
        return $this->totalEnergiaPresupuesto;
    }

    /**
     * Set precioEnergia
     *
     * @param string $precioEnergia
     *
     * @return PlanEstimadoIndicadores
     */
    public function setPrecioEnergia($precioEnergia)
    {
        $this->precioEnergia = $precioEnergia;

        return $this;
    }

    /**
     * Get precioEnergia
     *
     * @return string
     */
    public function getPrecioEnergia()
    {
        return $this->precioEnergia;
    }

    /**
     * Set totalEnergiaKW
     *
     * @param integer $totalEnergiaKW
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalEnergiaKW($totalEnergiaKW)
    {
        $this->totalEnergiaKW = $totalEnergiaKW;

        return $this;
    }

    /**
     * Get totalEnergiaKW
     *
     * @return integer
     */
    public function getTotalEnergiaKW()
    {
        return $this->totalEnergiaKW;
    }

    /**
     * Set totalMateriaPrima
     *
     * @param integer $totalMateriaPrima
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalMateriaPrima($totalMateriaPrima)
    {
        $this->totalMateriaPrima = $totalMateriaPrima;

        return $this;
    }

    /**
     * Get totalMateriaPrima
     *
     * @return integer
     */
    public function getTotalMateriaPrima()
    {
        return $this->totalMateriaPrima;
    }

    /**
     * Set totalDepreciacion
     *
     * @param integer $totalDepreciacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalDepreciacion($totalDepreciacion)
    {
        $this->totalDepreciacion = $totalDepreciacion;

        return $this;
    }

    /**
     * Get totalDepreciacion
     *
     * @return integer
     */
    public function getTotalDepreciacion()
    {
        return $this->totalDepreciacion;
    }

    /**
     * Set totalAmortizacion
     *
     * @param integer $totalAmortizacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setTotalAmortizacion($totalAmortizacion)
    {
        $this->totalAmortizacion = $totalAmortizacion;

        return $this;
    }

    /**
     * Get totalAmortizacion
     *
     * @return integer
     */
    public function getTotalAmortizacion()
    {
        return $this->totalAmortizacion;
    }

    /**
     * Set totalOtroIngreso
     *
     * @param integer $totalOtroIngreso
     *
     * @return PlanEstimadoIndicadores
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
     * Add planEstimadoDivisione
     *
     * @param \AppBundle\Entity\PlanEstimadoDivision $planEstimadoDivisione
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoDivisione(\AppBundle\Entity\PlanEstimadoDivision $planEstimadoDivisione)
    {
        $this->planEstimadoDivisiones[] = $planEstimadoDivisione;

        return $this;
    }

    /**
     * Remove planEstimadoDivisione
     *
     * @param \AppBundle\Entity\PlanEstimadoDivision $planEstimadoDivisione
     */
    public function removePlanEstimadoDivisione(\AppBundle\Entity\PlanEstimadoDivision $planEstimadoDivisione)
    {
        $this->planEstimadoDivisiones->removeElement($planEstimadoDivisione);
    }

    /**
     * Get planEstimadoDivisiones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoDivisiones()
    {
        return $this->planEstimadoDivisiones;
    }

    /**
     * Add planEstimadoRecursosHumano
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionSalario $planEstimadoRecursosHumano
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoRecursosHumano(\AppBundle\Entity\PlanEstimadoDivisionSalario $planEstimadoRecursosHumano)
    {
        $this->planEstimadoRecursosHumanos[] = $planEstimadoRecursosHumano;

        return $this;
    }

    /**
     * Remove planEstimadoRecursosHumano
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionSalario $planEstimadoRecursosHumano
     */
    public function removePlanEstimadoRecursosHumano(\AppBundle\Entity\PlanEstimadoDivisionSalario $planEstimadoRecursosHumano)
    {
        $this->planEstimadoRecursosHumanos->removeElement($planEstimadoRecursosHumano);
    }

    /**
     * Get planEstimadoRecursosHumanos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoRecursosHumanos()
    {
        return $this->planEstimadoRecursosHumanos;
    }

    /**
     * Add planEstimadoDivisionesMese
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionMes $planEstimadoDivisionesMese
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoDivisionesMese(\AppBundle\Entity\PlanEstimadoDivisionMes $planEstimadoDivisionesMese)
    {
        $this->planEstimadoDivisionesMeses[] = $planEstimadoDivisionesMese;

        return $this;
    }

    /**
     * Remove planEstimadoDivisionesMese
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionMes $planEstimadoDivisionesMese
     */
    public function removePlanEstimadoDivisionesMese(\AppBundle\Entity\PlanEstimadoDivisionMes $planEstimadoDivisionesMese)
    {
        $this->planEstimadoDivisionesMeses->removeElement($planEstimadoDivisionesMese);
    }

    /**
     * Get planEstimadoDivisionesMeses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoDivisionesMeses()
    {
        return $this->planEstimadoDivisionesMeses;
    }

    /**
     * Add planEstimadoRecursosHumanosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionMesSalario $planEstimadoRecursosHumanosMese
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoRecursosHumanosMese(\AppBundle\Entity\PlanEstimadoDivisionMesSalario $planEstimadoRecursosHumanosMese)
    {
        $this->planEstimadoRecursosHumanosMeses[] = $planEstimadoRecursosHumanosMese;

        return $this;
    }

    /**
     * Remove planEstimadoRecursosHumanosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoDivisionMesSalario $planEstimadoRecursosHumanosMese
     */
    public function removePlanEstimadoRecursosHumanosMese(\AppBundle\Entity\PlanEstimadoDivisionMesSalario $planEstimadoRecursosHumanosMese)
    {
        $this->planEstimadoRecursosHumanosMeses->removeElement($planEstimadoRecursosHumanosMese);
    }

    /**
     * Get planEstimadoRecursosHumanosMeses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoRecursosHumanosMeses()
    {
        return $this->planEstimadoRecursosHumanosMeses;
    }

    /**
     * Add planEstimadoCentrosCostosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoCentroCostoMes $planEstimadoCentrosCostosMese
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoCentrosCostosMese(\AppBundle\Entity\PlanEstimadoCentroCostoMes $planEstimadoCentrosCostosMese)
    {
        $this->planEstimadoCentrosCostosMeses[] = $planEstimadoCentrosCostosMese;

        return $this;
    }

    /**
     * Remove planEstimadoCentrosCostosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoCentroCostoMes $planEstimadoCentrosCostosMese
     */
    public function removePlanEstimadoCentrosCostosMese(\AppBundle\Entity\PlanEstimadoCentroCostoMes $planEstimadoCentrosCostosMese)
    {
        $this->planEstimadoCentrosCostosMeses->removeElement($planEstimadoCentrosCostosMese);
    }

    /**
     * Get planEstimadoCentrosCostosMeses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoCentrosCostosMeses()
    {
        return $this->planEstimadoCentrosCostosMeses;
    }

    /**
     * Add planEstimadoCentrosCostosRecursosHumanosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoCentroCostoMesSalario $planEstimadoCentrosCostosRecursosHumanosMese
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadoCentrosCostosRecursosHumanosMese(\AppBundle\Entity\PlanEstimadoCentroCostoMesSalario $planEstimadoCentrosCostosRecursosHumanosMese)
    {
        $this->planEstimadoCentrosCostosRecursosHumanosMeses[] = $planEstimadoCentrosCostosRecursosHumanosMese;

        return $this;
    }

    /**
     * Remove planEstimadoCentrosCostosRecursosHumanosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoCentroCostoMesSalario $planEstimadoCentrosCostosRecursosHumanosMese
     */
    public function removePlanEstimadoCentrosCostosRecursosHumanosMese(\AppBundle\Entity\PlanEstimadoCentroCostoMesSalario $planEstimadoCentrosCostosRecursosHumanosMese)
    {
        $this->planEstimadoCentrosCostosRecursosHumanosMeses->removeElement($planEstimadoCentrosCostosRecursosHumanosMese);
    }

    /**
     * Get planEstimadoCentrosCostosRecursosHumanosMeses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadoCentrosCostosRecursosHumanosMeses()
    {
        return $this->planEstimadoCentrosCostosRecursosHumanosMeses;
    }

    /**
     * Add planEstimadosOtrosGasto
     *
     * @param \AppBundle\Entity\PlanEstimadoOtrosGastos $planEstimadosOtrosGasto
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadosOtrosGasto(\AppBundle\Entity\PlanEstimadoOtrosGastos $planEstimadosOtrosGasto)
    {
        $this->planEstimadosOtrosGastos[] = $planEstimadosOtrosGasto;

        return $this;
    }

    /**
     * Remove planEstimadosOtrosGasto
     *
     * @param \AppBundle\Entity\PlanEstimadoOtrosGastos $planEstimadosOtrosGasto
     */
    public function removePlanEstimadosOtrosGasto(\AppBundle\Entity\PlanEstimadoOtrosGastos $planEstimadosOtrosGasto)
    {
        $this->planEstimadosOtrosGastos->removeElement($planEstimadosOtrosGasto);
    }

    /**
     * Get planEstimadosOtrosGastos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadosOtrosGastos()
    {
        return $this->planEstimadosOtrosGastos;
    }

    /**
     * Add planEstimadosMesOtrosGasto
     *
     * @param \AppBundle\Entity\PlanEstimadoMesOtrosGastos $planEstimadosMesOtrosGasto
     *
     * @return PlanEstimadoIndicadores
     */
    public function addPlanEstimadosMesOtrosGasto(\AppBundle\Entity\PlanEstimadoMesOtrosGastos $planEstimadosMesOtrosGasto)
    {
        $this->planEstimadosMesOtrosGastos[] = $planEstimadosMesOtrosGasto;

        return $this;
    }

    /**
     * Remove planEstimadosMesOtrosGasto
     *
     * @param \AppBundle\Entity\PlanEstimadoMesOtrosGastos $planEstimadosMesOtrosGasto
     */
    public function removePlanEstimadosMesOtrosGasto(\AppBundle\Entity\PlanEstimadoMesOtrosGastos $planEstimadosMesOtrosGasto)
    {
        $this->planEstimadosMesOtrosGastos->removeElement($planEstimadosMesOtrosGasto);
    }

    /**
     * Get planEstimadosMesOtrosGastos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanEstimadosMesOtrosGastos()
    {
        return $this->planEstimadosMesOtrosGastos;
    }

    /**
     * Set planReal
     *
     * @param boolean $planReal
     *
     * @return PlanEstimadoIndicadores
     */
    public function setPlanReal($planReal)
    {
        $this->planReal = $planReal;

        return $this;
    }

    /**
     * Get planReal
     *
     * @return boolean
     */
    public function getPlanReal()
    {
        return $this->planReal;
    }

    /**
     * Set aprobarPrespuestoDivisionBonificacion
     *
     * @param boolean $aprobarPrespuestoDivisionBonificacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionBonificacion($aprobarPrespuestoDivisionBonificacion)
    {
        $this->aprobarPrespuestoDivisionBonificacion = $aprobarPrespuestoDivisionBonificacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionBonificacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionBonificacion()
    {
        return $this->aprobarPrespuestoDivisionBonificacion;
    }

    /**
     * Set aprobarPrespuestoCentroCostoBonificacion
     *
     * @param boolean $aprobarPrespuestoCentroCostoBonificacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoBonificacion($aprobarPrespuestoCentroCostoBonificacion)
    {
        $this->aprobarPrespuestoCentroCostoBonificacion = $aprobarPrespuestoCentroCostoBonificacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoBonificacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoBonificacion()
    {
        return $this->aprobarPrespuestoCentroCostoBonificacion;
    }

    /**
     * Set aprobarPrespuestoTotalBonificacion
     *
     * @param boolean $aprobarPrespuestoTotalBonificacion
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalBonificacion($aprobarPrespuestoTotalBonificacion)
    {
        $this->aprobarPrespuestoTotalBonificacion = $aprobarPrespuestoTotalBonificacion;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalBonificacion
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalBonificacion()
    {
        return $this->aprobarPrespuestoTotalBonificacion;
    }

    /**
     * Set totalBonificacion
     *
     * @param integer $totalBonificacion
     *
     * @return PlanEstimadoIndicadores
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
     * Set aprobarPrespuestoDivisionComedor
     *
     * @param boolean $aprobarPrespuestoDivisionComedor
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoDivisionComedor($aprobarPrespuestoDivisionComedor)
    {
        $this->aprobarPrespuestoDivisionComedor = $aprobarPrespuestoDivisionComedor;

        return $this;
    }

    /**
     * Get aprobarPrespuestoDivisionComedor
     *
     * @return boolean
     */
    public function getAprobarPrespuestoDivisionComedor()
    {
        return $this->aprobarPrespuestoDivisionComedor;
    }

    /**
     * Set aprobarPrespuestoCentroCostoComedor
     *
     * @param boolean $aprobarPrespuestoCentroCostoComedor
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoCentroCostoComedor($aprobarPrespuestoCentroCostoComedor)
    {
        $this->aprobarPrespuestoCentroCostoComedor = $aprobarPrespuestoCentroCostoComedor;

        return $this;
    }

    /**
     * Get aprobarPrespuestoCentroCostoComedor
     *
     * @return boolean
     */
    public function getAprobarPrespuestoCentroCostoComedor()
    {
        return $this->aprobarPrespuestoCentroCostoComedor;
    }

    /**
     * Set aprobarPrespuestoTotalComedor
     *
     * @param boolean $aprobarPrespuestoTotalComedor
     *
     * @return PlanEstimadoIndicadores
     */
    public function setAprobarPrespuestoTotalComedor($aprobarPrespuestoTotalComedor)
    {
        $this->aprobarPrespuestoTotalComedor = $aprobarPrespuestoTotalComedor;

        return $this;
    }

    /**
     * Get aprobarPrespuestoTotalComedor
     *
     * @return boolean
     */
    public function getAprobarPrespuestoTotalComedor()
    {
        return $this->aprobarPrespuestoTotalComedor;
    }

    /**
     * Set totalComedor
     *
     * @param integer $totalComedor
     *
     * @return PlanEstimadoIndicadores
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
}
