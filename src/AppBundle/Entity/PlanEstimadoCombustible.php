<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoCombustible
 *
 * @ORM\Table(name="plan_estimado_combustible",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA22", columns={"tipo_combustible_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoCombustibleRepository")
 */
class PlanEstimadoCombustible
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
     * @ORM\Column(name="lts", type="integer",nullable=false)
     */
    private $lts = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="ltsMensual", type="integer",nullable=true)
     */
    private $ltsMensual = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="importe", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $importe = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="aprobadoEstimadoCombustibleMedioTransporte", type="boolean")
     */
    private $aprobadoEstimadoCombustibleMedioTransporte = false;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCombustible",inversedBy="planEstimadosCombustibles")
     */
    protected $tipoCombustible;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadosCombustibles")
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
     * Set lts
     *
     * @param integer $lts
     *
     * @return PlanEstimadoCombustible
     */
    public function setLts($lts)
    {
        $this->lts = $lts;

        return $this;
    }

    /**
     * Get lts
     *
     * @return integer
     */
    public function getLts()
    {
        return $this->lts;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return PlanEstimadoCombustible
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return PlanEstimadoCombustible
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set tipoCombustible
     *
     * @param \AppBundle\Entity\TipoCombustible $tipoCombustible
     *
     * @return PlanEstimadoCombustible
     */
    public function setTipoCombustible(\AppBundle\Entity\TipoCombustible $tipoCombustible = null)
    {
        $this->tipoCombustible = $tipoCombustible;

        return $this;
    }

    /**
     * Get tipoCombustible
     *
     * @return \AppBundle\Entity\TipoCombustible
     */
    public function getTipoCombustible()
    {
        return $this->tipoCombustible;
    }

    /**
     * Set planEstimadoIndicadores
     *
     * @param \AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores
     *
     * @return PlanEstimadoCombustible
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
     * Set aprobadoEstimadoCombustibleMedioTransporte
     *
     * @param boolean $aprobadoEstimadoCombustibleMedioTransporte
     *
     * @return PlanEstimadoCombustible
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
     * Set ltsMensual
     *
     * @param integer $ltsMensual
     *
     * @return PlanEstimadoCombustible
     */
    public function setLtsMensual($ltsMensual)
    {
        $this->ltsMensual = $ltsMensual;

        return $this;
    }

    /**
     * Get ltsMensual
     *
     * @return integer
     */
    public function getLtsMensual()
    {
        return $this->ltsMensual;
    }
}
