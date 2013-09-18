<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subtplanilla
 *
 * @ORM\Table(name="SUBTPLANILLA")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\SubtplanillaRepository")
 */
class Subtplanilla
{   
    
    /**
     * @ORM\Id
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     * @ORM\ManyToOne(targetEntity="Tplanilla", inversedBy="planillas")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL", nullable=true)
     */
    private $tipoPlanTpl;
    
    /**
     * @ORM\Id
     * @var string
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=true)
     */
    private $subtPlanStp;    

    /**
     * @var string
     * @ORM\Column(name="DESC_SUBT_STP", type="string", length=40, nullable=true)
     */
    private $descSubtStp;

    /**
     * @var string
     * @ORM\Column(name="TITU_SUBT_STP", type="string", length=40, nullable=true)
     */
    private $tituSubtStp;

    /**
     * @var string
     * @ORM\Column(name="OBSERV", type="text", nullable=true)
     */
    private $observ;

    public function __toString() {
        return $this->descSubtStp;
    }
    
    /**
     * Get subtPlanStp
     *
     * @return string 
     */
    public function getSubtPlanStp()
    {
        return $this->subtPlanStp;
    }
   

    /**
     * Set descSubtStp
     *
     * @param string $descSubtStp
     * @return Subtplanilla
     */
    public function setDescSubtStp($descSubtStp)
    {
        $this->descSubtStp = $descSubtStp;
    
        return $this;
    }

    /**
     * Get descSubtStp
     *
     * @return string 
     */
    public function getDescSubtStp()
    {
        return $this->descSubtStp;
    }

    /**
     * Set tituSubtStp
     *
     * @param string $tituSubtStp
     * @return Subtplanilla
     */
    public function setTituSubtStp($tituSubtStp)
    {
        $this->tituSubtStp = $tituSubtStp;
    
        return $this;
    }

    /**
     * Get tituSubtStp
     *
     * @return string 
     */
    public function getTituSubtStp()
    {
        return $this->tituSubtStp;
    }

    /**
     * Set observ
     *
     * @param string $observ
     * @return Subtplanilla
     */
    public function setObserv($observ)
    {
        $this->observ = $observ;
    
        return $this;
    }

    /**
     * Get observ
     *
     * @return string 
     */
    public function getObserv()
    {
        return $this->observ;
    }

    /**
     * Set tipoPlanTpl
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\tplanilla $tipoPlanTpl
     * @return Subtplanilla
     */
    public function setTipoPlanTpl(\Inei\Bundle\PayrollBundle\Entity\tplanilla $tipoPlanTpl = null)
    {
        $this->tipoPlanTpl = $tipoPlanTpl;
    
        return $this;
    }

    /**
     * Get tipoPlanTpl
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\tplanilla 
     */
    public function getTipoPlanTpl()
    {
        return $this->tipoPlanTpl;
    }
    

    /**
     * Set subtPlanStp
     *
     * @param string $subtPlanStp
     * @return Subtplanilla
     */
    public function setSubtPlanStp($subtPlanStp)
    {
        $this->subtPlanStp = $subtPlanStp;
    
        return $this;
    }
}
