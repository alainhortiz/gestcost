<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PlantillaCargo
 *
 * @ORM\Table(name="plantilla_cargo",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA05", columns={"centro_costo_id", "denominador_cargo_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlantillaCargoRepository")
 */
class PlantillaCargo
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
     * @ORM\Column(name="cantidad", type="integer",nullable=false)
     */
    private $cantidad = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TipoPlantillaCargo",inversedBy="plantillasCargos")
     */
    protected $tipoPlantilla;

    /**
     * @ORM\ManyToOne(targetEntity="CentroCosto",inversedBy="plantillasCargos")
     */
    protected $centroCosto;

    /**
     * @ORM\ManyToOne(targetEntity="DenominadorCargo",inversedBy="plantillasCargos")
     */
    protected $denominadorCargo;

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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return PlantillaCargo
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set tipoPlantilla
     *
     * @param \AppBundle\Entity\TipoPlantillaCargo $tipoPlantilla
     *
     * @return PlantillaCargo
     */
    public function setTipoPlantilla(\AppBundle\Entity\TipoPlantillaCargo $tipoPlantilla = null)
    {
        $this->tipoPlantilla = $tipoPlantilla;

        return $this;
    }

    /**
     * Get tipoPlantilla
     *
     * @return \AppBundle\Entity\TipoPlantillaCargo
     */
    public function getTipoPlantilla()
    {
        return $this->tipoPlantilla;
    }

    /**
     * Set centroCosto
     *
     * @param \AppBundle\Entity\CentroCosto $centroCosto
     *
     * @return PlantillaCargo
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
     * Set denominadorCargo
     *
     * @param \AppBundle\Entity\DenominadorCargo $denominadorCargo
     *
     * @return PlantillaCargo
     */
    public function setDenominadorCargo(\AppBundle\Entity\DenominadorCargo $denominadorCargo = null)
    {
        $this->denominadorCargo = $denominadorCargo;

        return $this;
    }

    /**
     * Get denominadorCargo
     *
     * @return \AppBundle\Entity\DenominadorCargo
     */
    public function getDenominadorCargo()
    {
        return $this->denominadorCargo;
    }

    /**
     * Set denominadorCargos
     *
     * @param \AppBundle\Entity\DenominadorCargo $denominadorCargos
     *
     * @return PlantillaCargo
     */
    public function setDenominadorCargos(\AppBundle\Entity\DenominadorCargo $denominadorCargos = null)
    {
        $this->denominadorCargos = $denominadorCargos;

        return $this;
    }

    /**
     * Get denominadorCargos
     *
     * @return \AppBundle\Entity\DenominadorCargo
     */
    public function getDenominadorCargos()
    {
        return $this->denominadorCargos;
    }
}
