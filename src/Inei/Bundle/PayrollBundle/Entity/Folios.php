<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Folios
 *
 * @ORM\Table(name="FOLIOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\FoliosRepository")
 */
class Folios
{    
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CODI_FOLIO", type="integer", nullable=false)
     */
    private $codiFolio;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="NUM_FOLIO", type="string", length=5, nullable=false)
     */
    private $folio;

    /**
     * @var string
     * 
     * @ORM\Column(name="PER_FOLIO", type="string", length=100, nullable=false)
     */
    private $periodoFolio;

    /**
     * @var integer
     * 
     * @ORM\Column(name="REG_FOLIO", type="integer", nullable=false)
     */
    private $registrosFolio;
    
    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Tomos
     * @ORM\ManyToOne(targetEntity="Tomos", inversedBy="folios")
     * @ORM\JoinColumn(name="CODI_TOMO", referencedColumnName="CODI_TOMO", nullable=false)
     */
    private $tomo;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     * @ORM\ManyToOne(targetEntity="Tplanilla", inversedBy="folios")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL")
     */
    private $tipoPlanTpl;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\subtplanilla
     * @ORM\ManyToOne(targetEntity="Subtplanilla", inversedBy="folios")
     * @ORM\JoinColumn(name="SUBT_PLAN_STP", referencedColumnName="SUBT_PLAN_STP")     
     */
    private $subtPlanTpl;

    /**
     * Get codiFolio
     *
     * @return integer 
     */
    public function getCodiFolio()
    {
        return $this->codiFolio;
    }

    /**
     * Set folio
     *
     * @param string $folio
     * @return Folios
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;
    
        return $this;
    }

    /**
     * Get folio
     *
     * @return string 
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Set periodoFolio
     *
     * @param string $periodoFolio
     * @return Folios
     */
    public function setPeriodoFolio($periodoFolio)
    {
        $this->periodoFolio = $periodoFolio;
    
        return $this;
    }

    /**
     * Get periodoFolio
     *
     * @return string 
     */
    public function getPeriodoFolio()
    {
        return $this->periodoFolio;
    }

    /**
     * Set registrosFolio
     *
     * @param integer $registrosFolio
     * @return Folios
     */
    public function setRegistrosFolio($registrosFolio)
    {
        $this->registrosFolio = $registrosFolio;
    
        return $this;
    }

    /**
     * Get registrosFolio
     *
     * @return integer 
     */
    public function getRegistrosFolio()
    {
        return $this->registrosFolio;
    }

    /**
     * Set tipoPlanTpl
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\TPlanilla $tipoPlanTpl
     * @return Folios
     */
    public function setTipoPlanTpl(\Inei\Bundle\PayrollBundle\Entity\TPlanilla $tipoPlanTpl = null)
    {
        $this->tipoPlanTpl = $tipoPlanTpl;
    
        return $this;
    }

    /**
     * Get tipoPlanTpl
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\TPlanilla 
     */
    public function getTipoPlanTpl()
    {
        return $this->tipoPlanTpl;
    }

    /**
     * Set subtPlanTpl
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Subtplanilla $subtPlanTpl
     * @return Folios
     */
    public function setSubtPlanTpl(\Inei\Bundle\PayrollBundle\Entity\Subtplanilla $subtPlanTpl = null)
    {
        $this->subtPlanTpl = $subtPlanTpl;
    
        return $this;
    }

    /**
     * Get subtPlanTpl
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Subtplanilla 
     */
    public function getSubtPlanTpl()
    {
        return $this->subtPlanTpl;
    }

    /**
     * Set tomo
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Tomos $tomo
     * @return Folios
     */
    public function setTomo(\Inei\Bundle\PayrollBundle\Entity\Tomos $tomo = null)
    {
        $this->tomo = $tomo;
    
        return $this;
    }

    /**
     * Get tomo
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Tomos 
     */
    public function getTomo()
    {
        return $this->tomo;
    }
}