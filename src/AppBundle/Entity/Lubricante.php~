<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lubricante
 *
 * @ORM\Table(name="lubricante")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LubricanteRepository")
 */
class Lubricante
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
     * @ORM\OneToMany(targetEntity="PrecioLubricante", mappedBy="lubricante")
     */
    private $preciosLubricantes;

    /**
     * @ORM\OneToMany(targetEntity="Transporte", mappedBy="lubricante")
     */
    private $transportes;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->preciosLubricantes = new ArrayCollection();
        $this->transportes = new ArrayCollection();
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
     * @return Lubricante
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
     * @return Lubricante
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
     * Add preciosLubricante
     *
     * @param \AppBundle\Entity\PrecioLubricante $preciosLubricante
     *
     * @return Lubricante
     */
    public function addPreciosLubricante(\AppBundle\Entity\PrecioLubricante $preciosLubricante)
    {
        $this->preciosLubricantes[] = $preciosLubricante;

        return $this;
    }

    /**
     * Remove preciosLubricante
     *
     * @param \AppBundle\Entity\PrecioLubricante $preciosLubricante
     */
    public function removePreciosLubricante(\AppBundle\Entity\PrecioLubricante $preciosLubricante)
    {
        $this->preciosLubricantes->removeElement($preciosLubricante);
    }

    /**
     * Get preciosLubricantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreciosLubricantes()
    {
        return $this->preciosLubricantes;
    }

    /**
     * Add transporte
     *
     * @param \AppBundle\Entity\Transporte $transporte
     *
     * @return Lubricante
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
}
