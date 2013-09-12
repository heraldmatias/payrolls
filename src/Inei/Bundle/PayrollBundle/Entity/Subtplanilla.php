<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subtplanilla
 */
class Subtplanilla
{
    /**
     * @var string
     */
    private $subtPlanStp;

    /**
     * @var string
     */
    private $tipoPlanTplId;

    /**
     * @var string
     */
    private $descSubtStp;

    /**
     * @var string
     */
    private $tituSubtStp;

    /**
     * @var string
     */
    private $observ;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     */
    private $tipoPlanTpl;


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
     * Set tipoPlanTplId
     *
     * @param string $tipoPlanTplId
     * @return Subtplanilla
     */
    public function setTipoPlanTplId($tipoPlanTplId)
    {
        $this->tipoPlanTplId = $tipoPlanTplId;
    
        return $this;
    }

    /**
     * Get tipoPlanTplId
     *
     * @return string 
     */
    public function getTipoPlanTplId()
    {
        return $this->tipoPlanTplId;
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
}
