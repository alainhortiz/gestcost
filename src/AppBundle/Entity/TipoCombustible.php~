<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoCombustible
 *
 * @ORM\Table(name="tipo_combustible")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoCombustibleRepository")
 */
class TipoCombustible
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
     * @ORM\Column(name="nombre", type="string", length=150, unique=true)
     */
    private $nombre;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="Transporte", mappedBy="tipoCombustible")
     */
    private $transportes;

    /**
     * @ORM\OneToMany(targetEntity="PrecioCombustible", mappedBy="tipoCombustible")
     */
    private $preciosCombustibles;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->transportes = new ArrayCollection();
        $this->preciosCombustibles = new ArrayCollection();
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
     * @return TipoCombustible
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
     * @return TipoCombustible
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
     * @return TipoCombustible
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
     * Add transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     *
     * @return TipoCombustible
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
     * Add preciosCombustible
     *
     * @param \AppBundle\Entity\PrecioCombustible $preciosCombustible
     *
     * @return TipoCombustible
     */
    public function addPreciosCombustible(\AppBundle\Entity\PrecioCombustible $preciosCombustible)
    {
        $this->preciosCombustibles[] = $preciosCombustible;

        return $this;
    }

    /**
     * Remove preciosCombustible
     *
     * @param \AppBundle\Entity\PrecioCombustible $preciosCombustible
     */
    public function removePreciosCombustible(\AppBundle\Entity\PrecioCombustible $preciosCombustible)
    {
        $this->preciosCombustibles->removeElement($preciosCombustible);
    }

    /**
     * Get preciosCombustibles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreciosCombustibles()
    {
        return $this->preciosCombustibles;
    }
}
