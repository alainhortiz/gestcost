<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanEstimadoMesCombustible
 *
 * @ORM\Table(name="plan_estimado_mes_combustible",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA24", columns={"mes","transporte_id", "plan_estimado_indicadores_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanEstimadoMesCombustibleRepository")
 */
class PlanEstimadoMesCombustible
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
     * @ORM\Column(name="ltsCombustible", type="integer",nullable=false)
     */
    private $ltsCombustible = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="precioCombustible", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $precioCombustible = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="importeCombustible", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $importeCombustible = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Transporte",inversedBy="planEstimadoMesesCombustibles")
     */
    protected $transporte;

    /**
     * @ORM\ManyToOne(targetEntity="PlanEstimadoIndicadores",inversedBy="planEstimadoMesesLubricantes")
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
     * @return PlanEstimadoMesCombustible
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
     * Set ltsCombustible
     *
     * @param integer $ltsCombustible
     *
     * @return PlanEstimadoMesCombustible
     */
    public function setLtsCombustible($ltsCombustible)
    {
        $this->ltsCombustible = $ltsCombustible;

        return $this;
    }

    /**
     * Get ltsCombustible
     *
     * @return integer
     */
    public function getLtsCombustible()
    {
        return $this->ltsCombustible;
    }

    /**
     * Set precioCombustible
     *
     * @param string $precioCombustible
     *
     * @return PlanEstimadoMesCombustible
     */
    public function setPrecioCombustible($precioCombustible)
    {
        $this->precioCombustible = $precioCombustible;

        return $this;
    }

    /**
     * Get precioCombustible
     *
     * @return string
     */
    public function getPrecioCombustible()
    {
        return $this->precioCombustible;
    }

    /**
     * Set importeCombustible
     *
     * @param string $importeCombustible
     *
     * @return PlanEstimadoMesCombustible
     */
    public function setImporteCombustible($importeCombustible)
    {
        $this->importeCombustible = $importeCombustible;

        return $this;
    }

    /**
     * Get importeCombustible
     *
     * @return string
     */
    public function getImporteCombustible()
    {
        return $this->importeCombustible;
    }

    /**
     * Set transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     *
     * @return PlanEstimadoMesCombustible
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
     * @return PlanEstimadoMesCombustible
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
