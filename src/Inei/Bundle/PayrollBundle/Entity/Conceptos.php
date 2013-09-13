<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conceptos
 *
 * @ORM\Table(name="CONCEPTOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\ConceptosRepository")
 */
class Conceptos
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODI_CONC_TCO", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="CONCEPTOS_CODI_CONC_TCO_seq", allocationSize=1, initialValue=1)
     */
    private $codiConcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_OPER_OPE", type="string", length=13, nullable=true)
     */
    private $codiOperOpe;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_CICL_CIC", type="string", length=1, nullable=true)
     */
    private $codiCiclCic;

    /**
     * @var string
     *
     * @ORM\Column(name="DESC_CONC_TCO", type="string", length=50, nullable=true)
     */
    private $descConcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="DESC_CORT_TCO", type="string", length=25, nullable=true)
     */
    private $descCortTco;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_CONC_TCO", type="string", length=1, nullable=true)
     */
    private $tipoConcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_CALC_TCO", type="string", length=1, nullable=true)
     */
    private $tipoCalcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="SECU_CALC_TCO", type="string", length=2, nullable=true)
     */
    private $secuCalcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_ASOC_TCO", type="string", length=1, nullable=true)
     */
    private $flagAsocTco;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_RECU_TCO", type="string", length=1, nullable=true)
     */
    private $flagRecuTco;

    /**
     * @var string
     *
     * @ORM\Column(name="RNTA_QNTA_TCO", type="string", length=1, nullable=true)
     */
    private $rntaQntaTco;

    /**
     * @var string
     *
     * @ORM\Column(name="CTS_CTS_TCO", type="string", length=1, nullable=true)
     */
    private $ctsCtsTco;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_CONC_ONC", type="string", length=5, nullable=true)
     */
    private $codiConcOnc;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_ENTI_ENT", type="string", length=8, nullable=true)
     */
    private $codiEntiEnt;

    /**
     * @var string
     *
     * @ORM\Column(name="CNTA_DEBE_TCO", type="string", length=15, nullable=true)
     */
    private $cntaDebeTco;

    /**
     * @var string
     *
     * @ORM\Column(name="CNTA_HABE_TCO", type="string", length=15, nullable=true)
     */
    private $cntaHabeTco;

    /**
     * @var string
     *
     * @ORM\Column(name="CLAS_CONC_TCO", type="string", length=1, nullable=true)
     */
    private $clasConcTco;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_PAGO_TCO", type="string", length=1, nullable=true)
     */
    private $flagPagoTco;

    /**
     * @var string
     *
     * @ORM\Column(name="SEDE_CONC_TCO", type="string", length=1, nullable=true)
     */
    private $sedeConcTco;



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
     * Set codiOperOpe
     *
     * @param string $codiOperOpe
     * @return Conceptos
     */
    public function setCodiOperOpe($codiOperOpe)
    {
        $this->codiOperOpe = $codiOperOpe;
    
        return $this;
    }

    /**
     * Get codiOperOpe
     *
     * @return string 
     */
    public function getCodiOperOpe()
    {
        return $this->codiOperOpe;
    }

    /**
     * Set codiCiclCic
     *
     * @param string $codiCiclCic
     * @return Conceptos
     */
    public function setCodiCiclCic($codiCiclCic)
    {
        $this->codiCiclCic = $codiCiclCic;
    
        return $this;
    }

    /**
     * Get codiCiclCic
     *
     * @return string 
     */
    public function getCodiCiclCic()
    {
        return $this->codiCiclCic;
    }

    /**
     * Set descConcTco
     *
     * @param string $descConcTco
     * @return Conceptos
     */
    public function setDescConcTco($descConcTco)
    {
        $this->descConcTco = $descConcTco;
    
        return $this;
    }

    /**
     * Get descConcTco
     *
     * @return string 
     */
    public function getDescConcTco()
    {
        return $this->descConcTco;
    }

    /**
     * Set descCortTco
     *
     * @param string $descCortTco
     * @return Conceptos
     */
    public function setDescCortTco($descCortTco)
    {
        $this->descCortTco = $descCortTco;
    
        return $this;
    }

    /**
     * Get descCortTco
     *
     * @return string 
     */
    public function getDescCortTco()
    {
        return $this->descCortTco;
    }

    /**
     * Set tipoConcTco
     *
     * @param string $tipoConcTco
     * @return Conceptos
     */
    public function setTipoConcTco($tipoConcTco)
    {
        $this->tipoConcTco = $tipoConcTco;
    
        return $this;
    }

    /**
     * Get tipoConcTco
     *
     * @return string 
     */
    public function getTipoConcTco()
    {
        return $this->tipoConcTco;
    }

    /**
     * Set tipoCalcTco
     *
     * @param string $tipoCalcTco
     * @return Conceptos
     */
    public function setTipoCalcTco($tipoCalcTco)
    {
        $this->tipoCalcTco = $tipoCalcTco;
    
        return $this;
    }

    /**
     * Get tipoCalcTco
     *
     * @return string 
     */
    public function getTipoCalcTco()
    {
        return $this->tipoCalcTco;
    }

    /**
     * Set secuCalcTco
     *
     * @param string $secuCalcTco
     * @return Conceptos
     */
    public function setSecuCalcTco($secuCalcTco)
    {
        $this->secuCalcTco = $secuCalcTco;
    
        return $this;
    }

    /**
     * Get secuCalcTco
     *
     * @return string 
     */
    public function getSecuCalcTco()
    {
        return $this->secuCalcTco;
    }

    /**
     * Set flagAsocTco
     *
     * @param string $flagAsocTco
     * @return Conceptos
     */
    public function setFlagAsocTco($flagAsocTco)
    {
        $this->flagAsocTco = $flagAsocTco;
    
        return $this;
    }

    /**
     * Get flagAsocTco
     *
     * @return string 
     */
    public function getFlagAsocTco()
    {
        return $this->flagAsocTco;
    }

    /**
     * Set flagRecuTco
     *
     * @param string $flagRecuTco
     * @return Conceptos
     */
    public function setFlagRecuTco($flagRecuTco)
    {
        $this->flagRecuTco = $flagRecuTco;
    
        return $this;
    }

    /**
     * Get flagRecuTco
     *
     * @return string 
     */
    public function getFlagRecuTco()
    {
        return $this->flagRecuTco;
    }

    /**
     * Set rntaQntaTco
     *
     * @param string $rntaQntaTco
     * @return Conceptos
     */
    public function setRntaQntaTco($rntaQntaTco)
    {
        $this->rntaQntaTco = $rntaQntaTco;
    
        return $this;
    }

    /**
     * Get rntaQntaTco
     *
     * @return string 
     */
    public function getRntaQntaTco()
    {
        return $this->rntaQntaTco;
    }

    /**
     * Set ctsCtsTco
     *
     * @param string $ctsCtsTco
     * @return Conceptos
     */
    public function setCtsCtsTco($ctsCtsTco)
    {
        $this->ctsCtsTco = $ctsCtsTco;
    
        return $this;
    }

    /**
     * Get ctsCtsTco
     *
     * @return string 
     */
    public function getCtsCtsTco()
    {
        return $this->ctsCtsTco;
    }

    /**
     * Set codiConcOnc
     *
     * @param string $codiConcOnc
     * @return Conceptos
     */
    public function setCodiConcOnc($codiConcOnc)
    {
        $this->codiConcOnc = $codiConcOnc;
    
        return $this;
    }

    /**
     * Get codiConcOnc
     *
     * @return string 
     */
    public function getCodiConcOnc()
    {
        return $this->codiConcOnc;
    }

    /**
     * Set codiEntiEnt
     *
     * @param string $codiEntiEnt
     * @return Conceptos
     */
    public function setCodiEntiEnt($codiEntiEnt)
    {
        $this->codiEntiEnt = $codiEntiEnt;
    
        return $this;
    }

    /**
     * Get codiEntiEnt
     *
     * @return string 
     */
    public function getCodiEntiEnt()
    {
        return $this->codiEntiEnt;
    }

    /**
     * Set cntaDebeTco
     *
     * @param string $cntaDebeTco
     * @return Conceptos
     */
    public function setCntaDebeTco($cntaDebeTco)
    {
        $this->cntaDebeTco = $cntaDebeTco;
    
        return $this;
    }

    /**
     * Get cntaDebeTco
     *
     * @return string 
     */
    public function getCntaDebeTco()
    {
        return $this->cntaDebeTco;
    }

    /**
     * Set cntaHabeTco
     *
     * @param string $cntaHabeTco
     * @return Conceptos
     */
    public function setCntaHabeTco($cntaHabeTco)
    {
        $this->cntaHabeTco = $cntaHabeTco;
    
        return $this;
    }

    /**
     * Get cntaHabeTco
     *
     * @return string 
     */
    public function getCntaHabeTco()
    {
        return $this->cntaHabeTco;
    }

    /**
     * Set clasConcTco
     *
     * @param string $clasConcTco
     * @return Conceptos
     */
    public function setClasConcTco($clasConcTco)
    {
        $this->clasConcTco = $clasConcTco;
    
        return $this;
    }

    /**
     * Get clasConcTco
     *
     * @return string 
     */
    public function getClasConcTco()
    {
        return $this->clasConcTco;
    }

    /**
     * Set flagPagoTco
     *
     * @param string $flagPagoTco
     * @return Conceptos
     */
    public function setFlagPagoTco($flagPagoTco)
    {
        $this->flagPagoTco = $flagPagoTco;
    
        return $this;
    }

    /**
     * Get flagPagoTco
     *
     * @return string 
     */
    public function getFlagPagoTco()
    {
        return $this->flagPagoTco;
    }

    /**
     * Set sedeConcTco
     *
     * @param string $sedeConcTco
     * @return Conceptos
     */
    public function setSedeConcTco($sedeConcTco)
    {
        $this->sedeConcTco = $sedeConcTco;
    
        return $this;
    }

    /**
     * Get sedeConcTco
     *
     * @return string 
     */
    public function getSedeConcTco()
    {
        return $this->sedeConcTco;
    }
}