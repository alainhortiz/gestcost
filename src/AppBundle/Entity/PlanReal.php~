<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlanReal
 *
 * @ORM\Table(name="plan_real")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRealRepository")
 */
class PlanReal
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
     * @var bool
     *
     * @ORM\Column(name="isInicio", type="boolean")
     */
    private $isInicio = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCerrado", type="boolean")
     */
    private $isCerrado = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isEnero", type="boolean")
     */
    private $isEnero = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isFebrero", type="boolean")
     */
    private $isFebrero = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isMarzo", type="boolean")
     */
    private $isMarzo = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAbril", type="boolean")
     */
    private $isAbril = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isMayo", type="boolean")
     */
    private $isMayo = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isJunio", type="boolean")
     */
    private $isJunio = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isJulio", type="boolean")
     */
    private $isJulio = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAgosto", type="boolean")
     */
    private $isAgosto = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isSeptiembre", type="boolean")
     */
    private $isSeptiembre = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isOctubre", type="boolean")
     */
    private $isOctubre = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isNoviembre", type="boolean")
     */
    private $isNoviembre = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDiciembre", type="boolean")
     */
    private $isDiciembre = false;

    /**
     * @ORM\OneToMany(targetEntity="PlanRealMes", mappedBy="planReal")
     */
    private $planRealMeses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->planRealMeses = new ArrayCollection();
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
     * Set year
     *
     * @param integer $year
     *
     * @return PlanReal
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
     * Set isInicio
     *
     * @param boolean $isInicio
     *
     * @return PlanReal
     */
    public function setIsInicio($isInicio)
    {
        $this->isInicio = $isInicio;

        return $this;
    }

    /**
     * Get isInicio
     *
     * @return boolean
     */
    public function getIsInicio()
    {
        return $this->isInicio;
    }

    /**
     * Set isCerrado
     *
     * @param boolean $isCerrado
     *
     * @return PlanReal
     */
    public function setIsCerrado($isCerrado)
    {
        $this->isCerrado = $isCerrado;

        return $this;
    }

    /**
     * Get isCerrado
     *
     * @return boolean
     */
    public function getIsCerrado()
    {
        return $this->isCerrado;
    }

    /**
     * Set isEnero
     *
     * @param boolean $isEnero
     *
     * @return PlanReal
     */
        public function setIsEnero($isEnero)
    {
        $this->isEnero = $isEnero;

        return $this;
    }

    /**
     * Get isEnero
     *
     * @return boolean
     */
    public function getIsEnero()
    {
        return $this->isEnero;
    }

    /**
     * Set isFebrero
     *
     * @param boolean $isFebrero
     *
     * @return PlanReal
     */
    public function setIsFebrero($isFebrero)
    {
        $this->isFebrero = $isFebrero;

        return $this;
    }

    /**
     * Get isFebrero
     *
     * @return boolean
     */
    public function getIsFebrero()
    {
        return $this->isFebrero;
    }

    /**
     * Set isMarzo
     *
     * @param boolean $isMarzo
     *
     * @return PlanReal
     */
    public function setIsMarzo($isMarzo)
    {
        $this->isMarzo = $isMarzo;

        return $this;
    }

    /**
     * Get isMarzo
     *
     * @return boolean
     */
    public function getIsMarzo()
    {
        return $this->isMarzo;
    }

    /**
     * Set isAbril
     *
     * @param boolean $isAbril
     *
     * @return PlanReal
     */
    public function setIsAbril($isAbril)
    {
        $this->isAbril = $isAbril;

        return $this;
    }

    /**
     * Get isAbril
     *
     * @return boolean
     */
    public function getIsAbril()
    {
        return $this->isAbril;
    }

    /**
     * Set isMayo
     *
     * @param boolean $isMayo
     *
     * @return PlanReal
     */
    public function setIsMayo($isMayo)
    {
        $this->isMayo = $isMayo;

        return $this;
    }

    /**
     * Get isMayo
     *
     * @return boolean
     */
    public function getIsMayo()
    {
        return $this->isMayo;
    }

    /**
     * Set isJunio
     *
     * @param boolean $isJunio
     *
     * @return PlanReal
     */
    public function setIsJunio($isJunio)
    {
        $this->isJunio = $isJunio;

        return $this;
    }

    /**
     * Get isJunio
     *
     * @return boolean
     */
    public function getIsJunio()
    {
        return $this->isJunio;
    }

    /**
     * Set isJulio
     *
     * @param boolean $isJulio
     *
     * @return PlanReal
     */
    public function setIsJulio($isJulio)
    {
        $this->isJulio = $isJulio;

        return $this;
    }

    /**
     * Get isJulio
     *
     * @return boolean
     */
    public function getIsJulio()
    {
        return $this->isJulio;
    }

    /**
     * Set isAgosto
     *
     * @param boolean $isAgosto
     *
     * @return PlanReal
     */
    public function setIsAgosto($isAgosto)
    {
        $this->isAgosto = $isAgosto;

        return $this;
    }

    /**
     * Get isAgosto
     *
     * @return boolean
     */
    public function getIsAgosto()
    {
        return $this->isAgosto;
    }

    /**
     * Set isSeptiembre
     *
     * @param boolean $isSeptiembre
     *
     * @return PlanReal
     */
    public function setIsSeptiembre($isSeptiembre)
    {
        $this->isSeptiembre = $isSeptiembre;

        return $this;
    }

    /**
     * Get isSeptiembre
     *
     * @return boolean
     */
    public function getIsSeptiembre()
    {
        return $this->isSeptiembre;
    }

    /**
     * Set isOctubre
     *
     * @param boolean $isOctubre
     *
     * @return PlanReal
     */
    public function setIsOctubre($isOctubre)
    {
        $this->isOctubre = $isOctubre;

        return $this;
    }

    /**
     * Get isOctubre
     *
     * @return boolean
     */
    public function getIsOctubre()
    {
        return $this->isOctubre;
    }

    /**
     * Set isNoviembre
     *
     * @param boolean $isNoviembre
     *
     * @return PlanReal
     */
    public function setIsNoviembre($isNoviembre)
    {
        $this->isNoviembre = $isNoviembre;

        return $this;
    }

    /**
     * Get isNoviembre
     *
     * @return boolean
     */
    public function getIsNoviembre()
    {
        return $this->isNoviembre;
    }

    /**
     * Set isDiciembre
     *
     * @param boolean $isDiciembre
     *
     * @return PlanReal
     */
    public function setIsDiciembre($isDiciembre)
    {
        $this->isDiciembre = $isDiciembre;

        return $this;
    }

    /**
     * Get isDiciembre
     *
     * @return boolean
     */
    public function getIsDiciembre()
    {
        return $this->isDiciembre;
    }

    /**
     * Add planRealMese
     *
     * @param \AppBundle\Entity\PlanRealMes $planRealMese
     *
     * @return PlanReal
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
