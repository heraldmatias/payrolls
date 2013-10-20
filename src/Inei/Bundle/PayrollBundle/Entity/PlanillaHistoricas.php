<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanillaHistoricas
 *
 * @ORM\Table(name="PLANILLA_HISTORICAS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\PlanillaHistoricasRepository")
 */
class PlanillaHistoricas
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="ANO_PERI_TPE", type="string", length=4) 
     */
    private $anoPeriTpe;

    /**
     * @var string
     * @ORM\Column(name="NUME_PERI_TPE", type="string", length=2) 
     */
    private $numePeriTpe;

    /**
     * @var float
     * @ORM\Column(name="VALO_CALC_PHI", type="string", length=150)
     */
    private $valoCalcPhi;

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
     * @var \Inei\Bundle\PayrollBundle\Entity\maestroPersonal     
     * @ORM\Column(name="CODI_EMPL_PER", type="string", length=100)
     */
    private $codiEmplPer;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\conceptos
     * @ORM\Column(name="CODI_CONC_TCO", type="string", length=5)
     */
    private $codiConcTco;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios     
     * @ORM\Column(name="CODI_FOLIO", type="integer", nullable=true)
     */
    private $folio;
    
    /**
     * @var string
     * @ORM\Column(name="DESC_PLAN_STP", type="text", nullable=True)
     */
    private $descripcion;
    
    /**
     * SI ES UN CAMPO MULTICONCEPTO, AQUI SE ALMACENA LA POSICION
     * QUE TIENE EL CONCEPTO EN LA LISTA TOTAL
     * 
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios     
     * @ORM\Column(name="FLAG_FOLIO", type="integer", nullable=true)
     */
    private $flag;
    
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
     * @ORM\Column(name="NUM_REG", type="integer", nullable=true)
     */
    private $registro;
    
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
     * @return PlanillaHistoricas
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
     * Set numePeriTpe
     *
     * @param string $numePeriTpe
     * @return PlanillaHistoricas
     */
    public function setNumePeriTpe($numePeriTpe)
    {
        $this->numePeriTpe = $numePeriTpe;
    
        return $this;
    }

    /**
     * Get numePeriTpe
     *
     * @return string 
     */
    public function getNumePeriTpe()
    {
        return $this->numePeriTpe;
    }
    
    /**
     * Set valoCalcPhi
     *
     * @param float $valoCalcPhi
     * @return PlanillaHistoricas
     */
    public function setValoCalcPhi($valoCalcPhi)
    {
        $this->valoCalcPhi = $valoCalcPhi;
    
        return $this;
    }

    /**
     * Get valoCalcPhi
     *
     * @return float 
     */
    public function getValoCalcPhi()
    {
        return $this->valoCalcPhi;
    }
    
    /**
     * Set tipoPlanTpl
     *
     * @param string $tipoPlanTpl
     * @return PlanillaHistoricas
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
     * @return PlanillaHistoricas
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
     * Set codiEmplPer
     *
     * @param string $codiEmplPer
     * @return PlanillaHistoricas
     */
    public function setCodiEmplPer($codiEmplPer)
    {
        $this->codiEmplPer = $codiEmplPer;

        return $this;
    }

    /**
     * Get codiEmplPer
     *
     * @return string 
     */
    public function getCodiEmplPer()
    {
        return $this->codiEmplPer;
    }

    /**
     * Set codiConcTco
     *
     * @param string $codiConcTco
     * @return PlanillaHistoricas
     */
    public function setCodiConcTco($codiConcTco)
    {
        $this->codiConcTco = $codiConcTco;

        return $this;
    }

    /**
     * Get codiConcTco
     *
     * @return string 
     */
    public function getCodiConcTco()
    {
        return $this->codiConcTco;
    }

    /**
     * Set folio
     *
     * @param integer $folio
     * @return PlanillaHistoricas
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;

        return $this;
    }

    /**
     * Get folio
     *
     * @return integer 
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return PlanillaHistoricas
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set flag
     *
     * @param integer $flag
     * @return PlanillaHistoricas
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return integer 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set creador
     *
     * @param integer $creador
     * @return PlanillaHistoricas
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
     * @return PlanillaHistoricas
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
     * @return PlanillaHistoricas
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
     * @return PlanillaHistoricas
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
     * Set registro
     *
     * @param integer $registro
     * @return PlanillaHistoricas
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;

        return $this;
    }

    /**
     * Get registro
     *
     * @return integer 
     */
    public function getRegistro()
    {
        return $this->registro;
    }
}
