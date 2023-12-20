<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OtroGasto
 *
 * @ORM\Table(name="otro_gasto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OtroGastoRepository")
 */
class OtroGasto
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
     * @ORM\Column(name="codigo", type="integer", unique=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150)
     */
    private $nombre;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\ManyToOne(targetEntity="TipoServicio",inversedBy="otrosGastos")
     */
    protected $tipoServicio;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoOtrosGastos", mappedBy="otroGasto")
     */
    private $planEstimadosOtrosGastos;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoMesOtrosGastos", mappedBy="otroGasto")
     */
    private $planEstimadosMesOtrosGastos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanRealMesServicio", mappedBy="otroGasto")
     */
    private $planRealMesesServicios;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->planEstimadosOtrosGastos = new ArrayCollection();
        $this->planEstimadosMesOtrosGastos = new ArrayCollection();
        $this->planRealMesesServicios = new ArrayCollection();
    }

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
     * Set codigo
     *
     * @param integer $codigo
     *
     * @return OtroGasto
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return OtroGasto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return OtroGasto
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add planEstimadosOtrosGasto
     *
     * @param \AppBundle\Entity\PlanEstimadoOtrosGastos $planEstimadosOtrosGasto
     *
     * @return OtroGasto
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
     * @return OtroGasto
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
     * Set tipoServicio
     *
     * @param \AppBundle\Entity\TipoServicio $tipoServicio
     *
     * @return OtroGasto
     */
    public function setTipoServicio(\AppBundle\Entity\TipoServicio $tipoServicio = null)
    {
        $this->tipoServicio = $tipoServicio;

        return $this;
    }

    /**
     * Get tipoServicio
     *
     * @return \AppBundle\Entity\TipoServicio
     */
    public function getTipoServicio()
    {
        return $this->tipoServicio;
    }

    /**
     * Add planRealMesesServicio
     *
     * @param \AppBundle\Entity\PlanRealMesServicio $planRealMesesServicio
     *
     * @return OtroGasto
     */
    public function addPlanRealMesesServicio(\AppBundle\Entity\PlanRealMesServicio $planRealMesesServicio)
    {
        $this->planRealMesesServicios[] = $planRealMesesServicio;

        return $this;
    }

    /**
     * Remove planRealMesesServicio
     *
     * @param \AppBundle\Entity\PlanRealMesServicio $planRealMesesServicio
     */
    public function removePlanRealMesesServicio(\AppBundle\Entity\PlanRealMesServicio $planRealMesesServicio)
    {
        $this->planRealMesesServicios->removeElement($planRealMesesServicio);
    }

    /**
     * Get planRealMesesServicios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanRealMesesServicios()
    {
        return $this->planRealMesesServicios;
    }
}
