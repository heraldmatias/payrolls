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
     * @ORM\ManyToOne(targetEntity="Tplanilla")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL", nullable=true)
     */
    private $tipoPlanTpl;
    
    /**
     * @ORM\Id
     * @var string
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=false)
     */
    private $subtPlanStp;    

    /**
     * @var string
     * @ORM\Column(name="DESC_SUBT_STP", type="string", length=40, nullable=false)
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
    
    /**
     * @var Inei\Bundle\AuthBundle\Entity\Usuarios
     * @ORM\ManyToOne(targetEntity="Inei\Bundle\AuthBundle\Entity\Usuarios")
     * @ORM\JoinColumn(name="USU_CREA_ID",referencedColumnName="id", nullable=true)
     */
    private $creador;
    
    /**
     * @var Inei\Bundle\AuthBundle\Entity\Usuarios
     * @ORM\ManyToOne(targetEntity="Inei\Bundle\AuthBundle\Entity\Usuarios")
     * @ORM\JoinColumn(name="USU_MOD_ID",referencedColumnName="id", nullable=true)
     */
    private $modificador;
    
    /**
     * @var datetime
     * @ORM\Column(name="FEC_CREAC", type="datetime", nullable=true)
     */
    private $fec_creac;
    
    /**
     * @var datetime
     * @ORM\Column(name="FEC_MOD", type="datetime", nullable=true)
     */
    private $fec_mod;

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

    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Subtplanilla
     */
    public function setFecCreac($fecCreac)
    {
        $this->fec_creac = $fecCreac;

        return $this;
    }

    /**
     * Get fec_creac
     *
     * @return \DateTime 
     */
    public function getFecCreac()
    {
        return $this->fec_creac;
    }

    /**
     * Set fec_mod
     *
     * @param \DateTime $fecMod
     * @return Subtplanilla
     */
    public function setFecMod($fecMod)
    {
        $this->fec_mod = $fecMod;

        return $this;
    }

    /**
     * Get fec_mod
     *
     * @return \DateTime 
     */
    public function getFecMod()
    {
        return $this->fec_mod;
    }

    /**
     * Set creador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $creador
     * @return Subtplanilla
     */
    public function setCreador(\Inei\Bundle\AuthBundle\Entity\Usuarios $creador = null)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * Set modificador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $modificador
     * @return Subtplanilla
     */
    public function setModificador(\Inei\Bundle\AuthBundle\Entity\Usuarios $modificador = null)
    {
        $this->modificador = $modificador;

        return $this;
    }

    /**
     * Get modificador
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getModificador()
    {
        return $this->modificador;
    }
}
