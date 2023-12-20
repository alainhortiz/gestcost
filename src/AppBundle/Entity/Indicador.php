<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indicador
 *
 * @ORM\Table(name="indicador")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IndicadorRepository")
 */
class Indicador
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
     * @ORM\Column(name="codigo", type="string", length=50, unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, unique=false)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="TipoIndicador",inversedBy="indicadores")
     */
    protected $tipoIndicador;

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
     * @param string $codigo
     *
     * @return Indicador
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
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
     * @return Indicador
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
     * Set tipoIndicador
     *
     * @param \AppBundle\Entity\TipoIndicador $tipoIndicador
     *
     * @return Indicador
     */
    public function setTipoIndicador(\AppBundle\Entity\TipoIndicador $tipoIndicador = null)
    {
        $this->tipoIndicador = $tipoIndicador;

        return $this;
    }

    /**
     * Get tipoIndicador
     *
     * @return \AppBundle\Entity\TipoIndicador
     */
    public function getTipoIndicador()
    {
        return $this->tipoIndicador;
    }
}
