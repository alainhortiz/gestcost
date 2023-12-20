<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlanRealMesServicio
 *
 * @ORM\Table(name="plan_real_mes_servicio",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA60", columns={"otro_gasto_id", "plan_real_mes_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRealMesServicioRepository")
 */
class PlanRealMesServicio
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
     * @var float
     *
     * @ORM\Column(name="total", type="decimal", precision=18, scale=2, nullable=true)
     */
    private $total = 0;

    /**
     * @ORM\ManyToOne(targetEntity="OtroGasto",inversedBy="planRealMesesServicios")
     */
    protected $otroGasto;

    /**
     * @ORM\ManyToOne(targetEntity="PlanRealMes",inversedBy="planRealMesesServicios")
     */
    protected $planRealMes;

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
     * Set total
     *
     * @param string $total
     *
     * @return PlanRealMesServicio
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set otroGasto
     *
     * @param \AppBundle\Entity\OtroGasto $otroGasto
     *
     * @return PlanRealMesServicio
     */
    public function setOtroGasto(\AppBundle\Entity\OtroGasto $otroGasto = null)
    {
        $this->otroGasto = $otroGasto;

        return $this;
    }

    /**
     * Get otroGasto
     *
     * @return \AppBundle\Entity\OtroGasto
     */
    public function getOtroGasto()
    {
        return $this->otroGasto;
    }

    /**
     * Set planRealMes
     *
     * @param \AppBundle\Entity\PlanRealMes $planRealMes
     *
     * @return PlanRealMesServicio
     */
    public function setPlanRealMes(\AppBundle\Entity\PlanRealMes $planRealMes = null)
    {
        $this->planRealMes = $planRealMes;

        return $this;
    }

    /**
     * Get planRealMes
     *
     * @return \AppBundle\Entity\PlanRealMes
     */
    public function getPlanRealMes()
    {
        return $this->planRealMes;
    }
}
