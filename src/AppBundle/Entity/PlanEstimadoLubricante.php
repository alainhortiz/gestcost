<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoLubricante
 *
 * @ORM\Table(name="plan_estimado_lubricante",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA23", columns={"lubricante_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoLubricanteRepository")
 */
class PlanEstimadoLubricante
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
     * @ORM\Column(name="aprobadoEstimadoLubricanteMedioTransporte", type="boolean")
     */
    private $aprobadoEstimadoLubricanteMedioTransporte = false;

    /**
     * @ORM\ManyToOne(targetEntity="Lubricante",inversedBy="planEstimadoLubricantes")
     */
    protected $lubricante;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoLubricantes")
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
     * Set lts
     *
     * @param integer $lts
     *
     * @return PlanEstimadoLubricante
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
     * Set ltsMensual
     *
     * @param integer $ltsMensual
     *
     * @return PlanEstimadoLubricante
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

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return PlanEstimadoLubricante
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
     * Set aprobadoEstimadoLubricanteMedioTransporte
     *
     * @param boolean $aprobadoEstimadoLubricanteMedioTransporte
     *
     * @return PlanEstimadoLubricante
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
     * Set lubricante
     *
     * @param \AppBundle\Entity\Lubricante $lubricante
     *
     * @return PlanEstimadoLubricante
     */
    public function setLubricante(\AppBundle\Entity\Lubricante $lubricante = null)
    {
        $this->lubricante = $lubricante;

        return $this;
    }

    /**
     * Get lubricante
     *
     * @return \AppBundle\Entity\Lubricante
     */
    public function getLubricante()
    {
        return $this->lubricante;
    }

    /**
     * Set planEstimadoIndicadores
     *
     * @param \AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores
     *
     * @return PlanEstimadoLubricante
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
