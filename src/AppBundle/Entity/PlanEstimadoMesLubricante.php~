<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoMesLubricante
 *
 * @ORM\Table(name="plan_estimado_mes_lubricante",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA35", columns={"mes","transporte_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoMesLubricanteRepository")
 */
class PlanEstimadoMesLubricante
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
     * @ORM\Column(name="ltsLubricante", type="integer",nullable=false)
     */
    private $ltsLubricante = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="precioLubricante", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $precioLubricante = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="importeLubricante", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $importeLubricante = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Transporte",inversedBy="planEstimadoMesesLubricantes")
     */
    protected $transporte;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoMesesLubricantes")
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
     * Set mes
     *
     * @param string $mes
     *
     * @return PlanEstimadoMesLubricante
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
     * Set ltsLubricante
     *
     * @param integer $ltsLubricante
     *
     * @return PlanEstimadoMesLubricante
     */
    public function setLtsLubricante($ltsLubricante)
    {
        $this->ltsLubricante = $ltsLubricante;

        return $this;
    }

    /**
     * Get ltsLubricante
     *
     * @return integer
     */
    public function getLtsLubricante()
    {
        return $this->ltsLubricante;
    }

    /**
     * Set precioLubricante
     *
     * @param string $precioLubricante
     *
     * @return PlanEstimadoMesLubricante
     */
    public function setPrecioLubricante($precioLubricante)
    {
        $this->precioLubricante = $precioLubricante;

        return $this;
    }

    /**
     * Get precioLubricante
     *
     * @return string
     */
    public function getPrecioLubricante()
    {
        return $this->precioLubricante;
    }

    /**
     * Set importeLubricante
     *
     * @param string $importeLubricante
     *
     * @return PlanEstimadoMesLubricante
     */
    public function setImporteLubricante($importeLubricante)
    {
        $this->importeLubricante = $importeLubricante;

        return $this;
    }

    /**
     * Get importeLubricante
     *
     * @return string
     */
    public function getImporteLubricante()
    {
        return $this->importeLubricante;
    }

    /**
     * Set transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     *
     * @return PlanEstimadoMesLubricante
     */
    public function setTransporte(\AppBundle\Entity\Transporte $transporte = null)
    {
        $this->transporte = $transporte;

        return $this;
    }

    /**
     * Get transporte
     *
     * @return \AppBundle\Entity\Transporte
     */
    public function getTransporte()
    {
        return $this->transporte;
    }

    /**
     * Set planEstimadoIndicadores
     *
     * @param \AppBundle\Entity\PlanEstimadoIndicadores $planEstimadoIndicadores
     *
     * @return PlanEstimadoMesLubricante
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
