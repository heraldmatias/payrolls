<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Conceptos
 *
 * @ORM\Table(name="CONCEPTOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\ConceptosRepository")
 */
class Conceptos {

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_CONC_TCO", type="string", length=5, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
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
     * @ORM\Column(name="DESC_CONC_TCO", type="string", length=150, nullable=false)
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
     * @ORM\OneToMany(targetEntity="ConceptosFolios", mappedBy="codiConcTco", fetch="EXTRA_LAZY")
     */
    private $folios;   
 
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
    
    private static $TIPOS = array(
        0 => 'Tiempo', 1 => 'Ingresos', 2 => 'Egresos', 3 => 'Aportaciones', 4 => 'Otros'
    );
    private static $CALCULOS = array(
        0 => 'Otros', 1 => 'Fijo', 2 => 'Formula'
    );
    private static $CLASE = array(
        1 => 'Fijo', 2 => 'Variable'
    );
    private static $PAGO = array(
        1 => 'INEI', 2 => 'MEF'
    );
    private static $SEDES = array(
        0 => 'Lima', 1 => 'ODEIS'
    );

    public function getTipoConcTco_display() {
        if (null === $this->tipoConcTco) {
            return;
        }
        return Conceptos::$TIPOS[$this->tipoConcTco];
    }

    public function getTipoCalcTco_display() {
        if (null === $this->tipoCalcTco) {
            return;
        }
        return Conceptos::$CALCULOS[$this->tipoCalcTco];
    }

    public function getClasConcTco_display() {
        if (null === $this->clasConcTco) {
            return;
        }
        return Conceptos::$CLASE[$this->clasConcTco];
    }

    public function getFlagPagoTco_display() {
        if (null === $this->flagPagoTco) {
            return;
        }
        return Conceptos::$PAGO[$this->flagPagoTco];
    }
    
    public function getSedeConcTco_display() {
        if (null === $this->sedeConcTco) {
            return;
        }
        return Conceptos::$SEDES[$this->sedeConcTco];
    }

    public function __toString() {
        return $this->getCodiConcTco() . ' - ' . $this->getDescCortTco();
    }

    /**
     * Get codiConcTco
     *
     * @return string 
     */
    public function getCodiConcTco() {
        return $this->codiConcTco;
    }

    /**
     * Set codiOperOpe
     *
     * @param string $codiOperOpe
     * @return Conceptos
     */
    public function setCodiOperOpe($codiOperOpe) {
        $this->codiOperOpe = $codiOperOpe;

        return $this;
    }

    /**
     * Get codiOperOpe
     *
     * @return string 
     */
    public function getCodiOperOpe() {
        return $this->codiOperOpe;
    }

    /**
     * Set codiCiclCic
     *
     * @param string $codiCiclCic
     * @return Conceptos
     */
    public function setCodiCiclCic($codiCiclCic) {
        $this->codiCiclCic = $codiCiclCic;

        return $this;
    }

    /**
     * Get codiCiclCic
     *
     * @return string 
     */
    public function getCodiCiclCic() {
        return $this->codiCiclCic;
    }

    /**
     * Set descConcTco
     *
     * @param string $descConcTco
     * @return Conceptos
     */
    public function setDescConcTco($descConcTco) {
        $this->descConcTco = $descConcTco;

        return $this;
    }

    /**
     * Get descConcTco
     *
     * @return string 
     */
    public function getDescConcTco() {
        return $this->descConcTco;
    }

    /**
     * Set descCortTco
     *
     * @param string $descCortTco
     * @return Conceptos
     */
    public function setDescCortTco($descCortTco) {
        $this->descCortTco = $descCortTco;

        return $this;
    }

    /**
     * Get descCortTco
     *
     * @return string 
     */
    public function getDescCortTco() {
        return $this->descCortTco;
    }

    /**
     * Set tipoConcTco
     *
     * @param string $tipoConcTco
     * @return Conceptos
     */
    public function setTipoConcTco($tipoConcTco) {
        $this->tipoConcTco = $tipoConcTco;

        return $this;
    }

    /**
     * Get tipoConcTco
     *
     * @return string 
     */
    public function getTipoConcTco() {
        return $this->tipoConcTco;
    }

    /**
     * Set tipoCalcTco
     *
     * @param string $tipoCalcTco
     * @return Conceptos
     */
    public function setTipoCalcTco($tipoCalcTco) {
        $this->tipoCalcTco = $tipoCalcTco;

        return $this;
    }

    /**
     * Get tipoCalcTco
     *
     * @return string 
     */
    public function getTipoCalcTco() {
        return $this->tipoCalcTco;
    }

    /**
     * Set secuCalcTco
     *
     * @param string $secuCalcTco
     * @return Conceptos
     */
    public function setSecuCalcTco($secuCalcTco) {
        $this->secuCalcTco = $secuCalcTco;

        return $this;
    }

    /**
     * Get secuCalcTco
     *
     * @return string 
     */
    public function getSecuCalcTco() {
        return $this->secuCalcTco;
    }

    /**
     * Set flagAsocTco
     *
     * @param string $flagAsocTco
     * @return Conceptos
     */
    public function setFlagAsocTco($flagAsocTco) {
        $this->flagAsocTco = $flagAsocTco;

        return $this;
    }

    /**
     * Get flagAsocTco
     *
     * @return string 
     */
    public function getFlagAsocTco() {
        return $this->flagAsocTco;
    }

    /**
     * Set flagRecuTco
     *
     * @param string $flagRecuTco
     * @return Conceptos
     */
    public function setFlagRecuTco($flagRecuTco) {
        $this->flagRecuTco = $flagRecuTco;

        return $this;
    }

    /**
     * Get flagRecuTco
     *
     * @return string 
     */
    public function getFlagRecuTco() {
        return $this->flagRecuTco;
    }

    /**
     * Set rntaQntaTco
     *
     * @param string $rntaQntaTco
     * @return Conceptos
     */
    public function setRntaQntaTco($rntaQntaTco) {
        $this->rntaQntaTco = $rntaQntaTco;

        return $this;
    }

    /**
     * Get rntaQntaTco
     *
     * @return string 
     */
    public function getRntaQntaTco() {
        return $this->rntaQntaTco;
    }

    /**
     * Set ctsCtsTco
     *
     * @param string $ctsCtsTco
     * @return Conceptos
     */
    public function setCtsCtsTco($ctsCtsTco) {
        $this->ctsCtsTco = $ctsCtsTco;

        return $this;
    }

    /**
     * Get ctsCtsTco
     *
     * @return string 
     */
    public function getCtsCtsTco() {
        return $this->ctsCtsTco;
    }

    /**
     * Set codiConcOnc
     *
     * @param string $codiConcOnc
     * @return Conceptos
     */
    public function setCodiConcOnc($codiConcOnc) {
        $this->codiConcOnc = $codiConcOnc;

        return $this;
    }

    /**
     * Get codiConcOnc
     *
     * @return string 
     */
    public function getCodiConcOnc() {
        return $this->codiConcOnc;
    }

    /**
     * Set codiEntiEnt
     *
     * @param string $codiEntiEnt
     * @return Conceptos
     */
    public function setCodiEntiEnt($codiEntiEnt) {
        $this->codiEntiEnt = $codiEntiEnt;

        return $this;
    }

    /**
     * Get codiEntiEnt
     *
     * @return string 
     */
    public function getCodiEntiEnt() {
        return $this->codiEntiEnt;
    }

    /**
     * Set cntaDebeTco
     *
     * @param string $cntaDebeTco
     * @return Conceptos
     */
    public function setCntaDebeTco($cntaDebeTco) {
        $this->cntaDebeTco = $cntaDebeTco;

        return $this;
    }

    /**
     * Get cntaDebeTco
     *
     * @return string 
     */
    public function getCntaDebeTco() {
        return $this->cntaDebeTco;
    }

    /**
     * Set cntaHabeTco
     *
     * @param string $cntaHabeTco
     * @return Conceptos
     */
    public function setCntaHabeTco($cntaHabeTco) {
        $this->cntaHabeTco = $cntaHabeTco;

        return $this;
    }

    /**
     * Get cntaHabeTco
     *
     * @return string 
     */
    public function getCntaHabeTco() {
        return $this->cntaHabeTco;
    }

    /**
     * Set clasConcTco
     *
     * @param string $clasConcTco
     * @return Conceptos
     */
    public function setClasConcTco($clasConcTco) {
        $this->clasConcTco = $clasConcTco;

        return $this;
    }

    /**
     * Get clasConcTco
     *
     * @return string 
     */
    public function getClasConcTco() {
        return $this->clasConcTco;
    }

    /**
     * Set flagPagoTco
     *
     * @param string $flagPagoTco
     * @return Conceptos
     */
    public function setFlagPagoTco($flagPagoTco) {
        $this->flagPagoTco = $flagPagoTco;

        return $this;
    }

    /**
     * Get flagPagoTco
     *
     * @return string 
     */
    public function getFlagPagoTco() {
        return $this->flagPagoTco;
    }

    /**
     * Set sedeConcTco
     *
     * @param string $sedeConcTco
     * @return Conceptos
     */
    public function setSedeConcTco($sedeConcTco) {
        $this->sedeConcTco = $sedeConcTco;

        return $this;
    }

    /**
     * Get sedeConcTco
     *
     * @return string 
     */
    public function getSedeConcTco() {
        return $this->sedeConcTco;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->folios = new ArrayCollection();
    }

    /**
     * Add folios
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $folios
     * @return Conceptos
     */
    public function addFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $folios) {
        $this->folios[] = $folios;
    }

    /**
     * Remove folios
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $folios
     */
    public function removeFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $folios) {
        $this->folios->removeElement($folios);
    }

    /**
     * Get folios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFolios() {
        return $this->folios;
    }

    /**
     * Set codiConcTco
     *
     * @param string $codiConcTco
     * @return Conceptos
     */
    public function setCodiConcTco($codiConcTco) {
        $this->codiConcTco = $codiConcTco;

        return $this;
    } 


    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Conceptos
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
     * @return Conceptos
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
     * @return Conceptos
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
     * @return Conceptos
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
