<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PrecioCombustible
 *
 * @ORM\Table(name="precio_combustible",uniqueConstraints={@UniqueConstraint(name="IDX__UNIQUETUPLA30", columns={"year", "mes", "tipo_combustible_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrecioCombustibleRepository")
 */
class PrecioCombustible
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
     * @ORM\ManyToOne(targetEntity="TipoCombustible",inversedBy="preciosCombustibles")
     */
    protected $tipoCombustible;


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
     * Set year
     *
     * @param integer $year
     *
     * @return PrecioCombustible
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

    /**
     * Set mes
     *
     * @param string $mes
     *
     * @return PrecioCombustible
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
     * @return PrecioCombustible
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
     * Set tipoCombustible
     *
     * @param \AppBundle\Entity\TipoCombustible $tipoCombustible
     *
     * @return PrecioCombustible
     */
    public function setTipoCombustible(\AppBundle\Entity\TipoCombustible $tipoCombustible = null)
    {
        $this->tipoCombustible = $tipoCombustible;

        return $this;
    }

    /**
     * Get tipoCombustible
     *
     * @return \AppBundle\Entity\TipoCombustible
     */
    public function getTipoCombustible()
    {
        return $this->tipoCombustible;
    }
}
