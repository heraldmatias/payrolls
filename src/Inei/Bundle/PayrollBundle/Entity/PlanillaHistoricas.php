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
     */
    private $tipoPlanTplId;

    /**
     * @var string
     */
    private $subtPlanTplId;

    /**
     * @var string
     */
    private $anoPeriTpe;

    /**
     * @var string
     */
    private $numePeriTpe;

    /**
     * @var string
     */
    private $codiEmplPerId;

    /**
     * @var string
     */
    private $codiConcTcoId;

    /**
     * @var float
     */
    private $valoCalcPhi;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     * @ORM\ManyToOne(targetEntity="TPlanilla", inversedBy="planillas")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL")
     */
    private $tipoPlanTpl;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\subtplanilla     
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=true )
     */
    private $subtPlanTpl;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\maestroPersonal
     * @ORM\ManyToOne(targetEntity="MaestroPersonal", inversedBy="empleados")
     * @ORM\JoinColumn(name="CODI_EMPL_PER", referencedColumnName="CODI_EMPL_PER")
     */
    private $codiEmplPer;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\conceptos
     * @ORM\ManyToOne(targetEntity="Conceptos")
     * @ORM\JoinColumn(name="CODI_CONC_TCO", referencedColumnName="CODI_CONC_TCO")
     */
    private $codiConcTco;


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
     * Set tipoPlanTplId
     *
     * @param string $tipoPlanTplId
     * @return PlanillaHistoricas
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
     * Set subtPlanTplId
     *
     * @param string $subtPlanTplId
     * @return PlanillaHistoricas
     */
    public function setSubtPlanTplId($subtPlanTplId)
    {
        $this->subtPlanTplId = $subtPlanTplId;
    
        return $this;
    }

    /**
     * Get subtPlanTplId
     *
     * @return string 
     */
    public function getSubtPlanTplId()
    {
        return $this->subtPlanTplId;
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
     * Set codiEmplPerId
     *
     * @param string $codiEmplPerId
     * @return PlanillaHistoricas
     */
    public function setCodiEmplPerId($codiEmplPerId)
    {
        $this->codiEmplPerId = $codiEmplPerId;
    
        return $this;
    }

    /**
     * Get codiEmplPerId
     *
     * @return string 
     */
    public function getCodiEmplPerId()
    {
        return $this->codiEmplPerId;
    }

    /**
     * Set codiConcTcoId
     *
     * @param string $codiConcTcoId
     * @return PlanillaHistoricas
     */
    public function setCodiConcTcoId($codiConcTcoId)
    {
        $this->codiConcTcoId = $codiConcTcoId;
    
        return $this;
    }

    /**
     * Get codiConcTcoId
     *
     * @return string 
     */
    public function getCodiConcTcoId()
    {
        return $this->codiConcTcoId;
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
     * @param \Inei\Bundle\PayrollBundle\Entity\tplanilla $tipoPlanTpl
     * @return PlanillaHistoricas
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
     * Set subtPlanTpl
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\subtplanilla $subtPlanTpl
     * @return PlanillaHistoricas
     */
    public function setSubtPlanTpl(\Inei\Bundle\PayrollBundle\Entity\subtplanilla $subtPlanTpl = null)
    {
        $this->subtPlanTpl = $subtPlanTpl;
    
        return $this;
    }

    /**
     * Get subtPlanTpl
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\subtplanilla 
     */
    public function getSubtPlanTpl()
    {
        return $this->subtPlanTpl;
    }

    /**
     * Set codiEmplPer
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\maestroPersonal $codiEmplPer
     * @return PlanillaHistoricas
     */
    public function setCodiEmplPer(\Inei\Bundle\PayrollBundle\Entity\maestroPersonal $codiEmplPer = null)
    {
        $this->codiEmplPer = $codiEmplPer;
    
        return $this;
    }

    /**
     * Get codiEmplPer
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\maestroPersonal 
     */
    public function getCodiEmplPer()
    {
        return $this->codiEmplPer;
    }

    /**
     * Set codiConcTco
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\conceptos $codiConcTco
     * @return PlanillaHistoricas
     */
    public function setCodiConcTco(\Inei\Bundle\PayrollBundle\Entity\conceptos $codiConcTco = null)
    {
        $this->codiConcTco = $codiConcTco;
    
        return $this;
    }

    /**
     * Get codiConcTco
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\conceptos 
     */
    public function getCodiConcTco()
    {
        return $this->codiConcTco;
    }
}
