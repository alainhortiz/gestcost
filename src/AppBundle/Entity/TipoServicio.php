<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoServicio
 *
 * @ORM\Table(name="tipo_servicio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoServicioRepository")
 */
class TipoServicio
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
     * @ORM\Column(name="nombre", type="string", length=100, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="OtroGasto", mappedBy="tipoServicio")
     */
    private $otrosGastos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->otrosGastos = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TipoServicio
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
     * Add otrosGasto
     *
     * @param \AppBundle\Entity\OtroGasto $otrosGasto
     *
     * @return TipoServicio
     */
    public function addOtrosGasto(\AppBundle\Entity\OtroGasto $otrosGasto)
    {
        $this->otrosGastos[] = $otrosGasto;

        return $this;
    }

    /**
     * Remove otrosGasto
     *
     * @param \AppBundle\Entity\OtroGasto $otrosGasto
     */
    public function removeOtrosGasto(\AppBundle\Entity\OtroGasto $otrosGasto)
    {
        $this->otrosGastos->removeElement($otrosGasto);
    }

    /**
     * Get otrosGastos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOtrosGastos()
    {
        return $this->otrosGastos;
    }
}
