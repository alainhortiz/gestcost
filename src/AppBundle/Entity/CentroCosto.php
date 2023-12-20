<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CentroCosto
 *
 * @ORM\Table(name="centro_costo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CentroCostoRepository")
 */
class CentroCosto
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
     * @ORM\Column(name="codigo", type="integer", unique=true)
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
     * @ORM\ManyToOne(targetEntity="Provincia",inversedBy="centrosCostos")
     */
    protected $provincia;

    /**
     * @ORM\ManyToOne(targetEntity="DivisionCentroCosto",inversedBy="centrosCostos")
     */
    protected $divisionCentroCosto;

    /**
     * @ORM\OneToMany(targetEntity="Transporte", mappedBy="centroCosto")
     */
    private $transportes;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="centroCosto")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="PlantillaCargo", mappedBy="centroCosto")
     */
    private $plantillasCargos;

    /**
     * @ORM\OneToMany(targetEntity="PlanEstimadoCentroCostoMes", mappedBy="centroCosto")
     */
    private $planEstimadoCentrosCostosMeses;

    /**
     * @ORM\OneToMany(targetEntity="PlanRealMes", mappedBy="centroCosto")
     */
    private $planRealMeses;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->transportes = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
        $this->plantillasCargos = new ArrayCollection();
        $this->planEstimadoCentrosCostosMeses = new ArrayCollection();
        $this->planRealMeses = new ArrayCollection();
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
     * Set codigo
     *
     * @param integer $codigo
     *
     * @return CentroCosto
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer
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
     * @return CentroCosto
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
     * @return CentroCosto
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set provincia
     *
     * @param \AppBundle\Entity\Provincia $provincia
     *
     * @return CentroCosto
     */
    public function setProvincia(\AppBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \AppBundle\Entity\Provincia
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set divisionCentroCosto
     *
     * @param \AppBundle\Entity\DivisionCentroCosto $divisionCentroCosto
     *
     * @return CentroCosto
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
     * Add transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     *
     * @return CentroCosto
     */
    public function addTransporte(\AppBundle\Entity\Transporte $transporte)
    {
        $this->transportes[] = $transporte;

        return $this;
    }

    /**
     * Remove transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     */
    public function removeTransporte(\AppBundle\Entity\Transporte $transporte)
    {
        $this->transportes->removeElement($transporte);
    }

    /**
     * Get transportes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransportes()
    {
        return $this->transportes;
    }

    /**
     * Add usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return CentroCosto
     */
    public function addUsuario(\AppBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\AppBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add plantillasCargo
     *
     * @param \AppBundle\Entity\PlantillaCargo $plantillasCargo
     *
     * @return CentroCosto
     */
    public function addPlantillasCargo(\AppBundle\Entity\PlantillaCargo $plantillasCargo)
    {
        $this->plantillasCargos[] = $plantillasCargo;

        return $this;
    }

    /**
     * Remove plantillasCargo
     *
     * @param \AppBundle\Entity\PlantillaCargo $plantillasCargo
     */
    public function removePlantillasCargo(\AppBundle\Entity\PlantillaCargo $plantillasCargo)
    {
        $this->plantillasCargos->removeElement($plantillasCargo);
    }

    /**
     * Get plantillasCargos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlantillasCargos()
    {
        return $this->plantillasCargos;
    }

    /**
     * Add planEstimadoCentrosCostosMese
     *
     * @param \AppBundle\Entity\PlanEstimadoCentroCostoMes $planEstimadoCentrosCostosMese
     *
     * @return CentroCosto
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
     * Add planRealMese
     *
     * @param \AppBundle\Entity\PlanRealMes $planRealMese
     *
     * @return CentroCosto
     */
    public function addPlanRealMese(\AppBundle\Entity\PlanRealMes $planRealMese)
    {
        $this->planRealMeses[] = $planRealMese;

        return $this;
    }

    /**
     * Remove planRealMese
     *
     * @param \AppBundle\Entity\PlanRealMes $planRealMese
     */
    public function removePlanRealMese(\AppBundle\Entity\PlanRealMes $planRealMese)
    {
        $this->planRealMeses->removeElement($planRealMese);
    }

    /**
     * Get planRealMeses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanRealMeses()
    {
        return $this->planRealMeses;
    }
}
