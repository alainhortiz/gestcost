<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoIndicador
 *
 * @ORM\Table(name="tipo_indicador")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoIndicadorRepository")
 */
class TipoIndicador
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
     * @ORM\OneToMany(targetEntity="Indicador", mappedBy="tipoIndicador")
     */
    private $indicadores;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->indicadores = new ArrayCollection();
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
     * @return TipoIndicador
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
     * Add indicadore
     *
     * @param \AppBundle\Entity\Indicador $indicadore
     *
     * @return TipoIndicador
     */
    public function addIndicadore(\AppBundle\Entity\Indicador $indicadore)
    {
        $this->indicadores[] = $indicadore;

        return $this;
    }

    /**
     * Remove indicadore
     *
     * @param \AppBundle\Entity\Indicador $indicadore
     */
    public function removeIndicadore(\AppBundle\Entity\Indicador $indicadore)
    {
        $this->indicadores->removeElement($indicadore);
    }

    /**
     * Get indicadores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIndicadores()
    {
        return $this->indicadores;
    }
}
