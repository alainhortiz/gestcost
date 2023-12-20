<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Provincia
 *
 * @ORM\Table(name="provincia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProvinciaRepository")
 */
class Provincia
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
     * @ORM\Column(name="nombre", type="string", length=150, unique=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="CentroCosto", mappedBy="provincia")
     */
    private $centrosCostos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->centrosCostos = new ArrayCollection();
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
     * @return Provincia
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
     * Add centrosCosto
     *
     * @param \AppBundle\Entity\CentroCosto $centrosCosto
     *
     * @return Provincia
     */
    public function addCentrosCosto(\AppBundle\Entity\CentroCosto $centrosCosto)
    {
        $this->centrosCostos[] = $centrosCosto;

        return $this;
    }

    /**
     * Remove centrosCosto
     *
     * @param \AppBundle\Entity\CentroCosto $centrosCosto
     */
    public function removeCentrosCosto(\AppBundle\Entity\CentroCosto $centrosCosto)
    {
        $this->centrosCostos->removeElement($centrosCosto);
    }

    /**
     * Get centrosCostos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosCostos()
    {
        return $this->centrosCostos;
    }
}
