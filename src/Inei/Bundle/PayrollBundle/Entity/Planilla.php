<?php

/**
 * Description of Planilla
 *
 * @author holivares
 */
namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanillaHistoricas
 *
 * @ORM\Table(name="PLANILLA")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\PlanillaRepository")
 */
class Planilla {
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="ANO_PERI_TPE", type="string", length=30) 
     */
    private $anoPeriTpe;

    /**
     * @ORM\Column(name="TIPO_PLAN_TPL", type="string", length=5)
     */
    private $tipoPlanTpl;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\subtplanilla     
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=true )
     */
    private $subtPlanTpl;    
    
    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios
     * @ORM\ManyToOne(targetEntity="Folios")
     * @ORM\JoinColumn(name="CODI_FOLIO", referencedColumnName="CODI_FOLIO", nullable=true)
     */
     private $folio;
    
    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Tomos
     * @ORM\ManyToOne(targetEntity="Tomos")
     * @ORM\JoinColumn(name="CODI_TOMO", referencedColumnName="CODI_TOMO", nullable=true)
     */
     private $tomo;
     
    /**
     * @var integer
     * @ORM\Column(name="USU_CREA_ID", type="integer", nullable=true)
     */
    private $creador;
    
    /**
     * @var integer
     * @ORM\Column(name="USU_MOD_ID", type="integer", nullable=true)
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
    
    /**
     * @var integer
     * @ORM\Column(name="CANT_REG", type="integer", nullable=true)
     */
    private $registros;

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
     * Set anoPeriTpe
     *
     * @param string $anoPeriTpe
     * @return Planilla
     */
    public function setAnoPeriTpe($anoPeriTpe)
    {
        $this->anoPeriTpe = $anoPeriTpe;

        return $this;
    }

    /**
     * Get anoPeriTpe
     *
     * @return string 
     */
    public function getAnoPeriTpe()
    {
        return $this->anoPeriTpe;
    }

    /**
     * Set tipoPlanTpl
     *
     * @param string $tipoPlanTpl
     * @return Planilla
     */
    public function setTipoPlanTpl($tipoPlanTpl)
    {
        $this->tipoPlanTpl = $tipoPlanTpl;

        return $this;
    }

    /**
     * Get tipoPlanTpl
     *
     * @return string 
     */
    public function getTipoPlanTpl()
    {
        return $this->tipoPlanTpl;
    }

    /**
     * Set subtPlanTpl
     *
     * @param string $subtPlanTpl
     * @return Planilla
     */
    public function setSubtPlanTpl($subtPlanTpl)
    {
        $this->subtPlanTpl = $subtPlanTpl;

        return $this;
    }

    /**
     * Get subtPlanTpl
     *
     * @return string 
     */
    public function getSubtPlanTpl()
    {
        return $this->subtPlanTpl;
    }
    
    /**
     * Set creador
     *
     * @param integer $creador
     * @return Planilla
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return integer 
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * Set modificador
     *
     * @param integer $modificador
     * @return Planilla
     */
    public function setModificador($modificador)
    {
        $this->modificador = $modificador;

        return $this;
    }

    /**
     * Get modificador
     *
     * @return integer 
     */
    public function getModificador()
    {
        return $this->modificador;
    }

    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Planilla
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
     * @return Planilla
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
     * Set registros
     *
     * @param integer $registros
     * @return Planilla
     */
    public function setRegistros($registros)
    {
        $this->registros = $registros;

        return $this;
    }

    /**
     * Get registros
     *
     * @return integer 
     */
    public function getRegistros()
    {
        return $this->registros;
    }

    /**
     * Set tomo
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Tomos $tomo
     * @return Planilla
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

    /**
     * Set folio
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $folio
     * @return Planilla
     */
    public function setFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $folio = null)
    {
        $this->folio = $folio;

        return $this;
    }

    /**
     * Get folio
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Folios 
     */
    public function getFolio()
    {
        return $this->folio;
    }
}
