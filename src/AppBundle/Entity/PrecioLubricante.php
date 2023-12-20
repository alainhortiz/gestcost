<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PrecioLubricante
 *
 * @ORM\Table(name="precio_lubricante",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA31", columns={"year", "mes", "lubricante_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrecioLubricanteRepository")
 */
class PrecioLubricante
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
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="mes", type="string", length=10)
     */
    private $mes;

    /**
     * @var int
     *
     * @ORM\Column(name="precio", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $precio = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Lubricante",inversedBy="preciosLubricantes")
     */
    protected $lubricante;

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
     * Set mes
     *
     * @param string $mes
     *
     * @return PrecioLubricante
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return PrecioLubricante
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set lubricante
     *
     * @param \AppBundle\Entity\Lubricante $lubricante
     *
     * @return PrecioLubricante
     */
    public function setLubricante(\AppBundle\Entity\Lubricante $lubricante = null)
    {
        $this->lubricante = $lubricante;

        return $this;
    }

    /**
     * Get lubricante
     *
     * @return \AppBundle\Entity\Lubricante
     */
    public function getLubricante()
    {
        return $this->lubricante;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return PrecioLubricante
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }
}
