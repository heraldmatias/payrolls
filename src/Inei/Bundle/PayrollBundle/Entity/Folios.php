<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * Folios
 *
 * @ORM\Table(name="FOLIOS",
 * indexes={
 * @ORM\Index(name="NUM_FOLIO_idx", columns={"NUM_FOLIO"}),
 * @ORM\Index(name="per_folio_idx", columns={"PER_FOLIO"})
 * })
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\FoliosRepository")
 * @HasLifecycleCallbacks
 */
class Folios {

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="CODI_FOLIO", type="integer", nullable=false)
     */
    private $codiFolio;

    /**
     * @var string
     * @ORM\Column(name="NUM_FOLIO", type="integer", nullable=false)
     */
    private $folio;

    /**
     * @var string
     * 
     * @ORM\Column(name="PER_FOLIO", type="string", length=100, nullable=true)
     */
    private $periodoFolio;

    /**
     * @var integer
     * 
     * @ORM\Column(name="REG_FOLIO", type="integer", nullable=true)
     */
    private $registrosFolio;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Tomos
     * @ORM\ManyToOne(targetEntity="Tomos", inversedBy="folios")
     * @ORM\JoinColumn(name="CODI_TOMO", referencedColumnName="CODI_TOMO", nullable=true)
     * @ORM\OrderBy({"codiTomo" = "ASC"})
     */
    private $tomo;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     * @ORM\ManyToOne(targetEntity="Tplanilla")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL", nullable=true)
     */
    private $tipoPlanTpl;

    /**
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=true)     
     */
    private $subtPlanStp;

    /**
     *
     * @ORM\OneToMany(targetEntity="ConceptosFolios", mappedBy="codiFolio", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"orden" = "ASC"})
     */
    private $conceptos;
    private $rmconceptos;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas
     * 
     */
    //@ORM\OneToMany(targetEntity="PlanillaHistoricas", mappedBy="folio", cascade={"persist"}, fetch="EXTRA_LAZY")
    private $planillas;
    private $descSubtStp;

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
    private static $PERIODOS = array(
        '01' => 'ENERO',
        '02' => 'FEBRERO',
        '03' => 'MARZO',
        '04' => 'ABRIL',
        '05' => 'MAYO',
        '06' => 'JUNIO',
        '07' => 'JULIO',
        '08' => 'AGOSTO',
        '09' => 'SETIEMBRE',
        '10' => 'OCTUBRE',
        '11' => 'NOVIEMBRE',
        '12' => 'DICIEMBRE'
    );
    
    public function getPeriodoDisplayFolio() {
        return array_key_exists($this->periodoFolio, self::$PERIODOS)?
        self::$PERIODOS[$this->periodoFolio]:$this->periodoFolio;
    }

    public function setDescSubtStp($descSubtStp) {
        $this->descSubtStp = $descSubtStp;
    }

    public function getDescSubtStp() {
        return $this->descSubtStp;
    }

    public function __construct() {
        $this->conceptos = new ArrayCollection();
        $this->rmconceptos = array();
        //$this->planillas = new ArrayCollection();
    }

    /**
     * Get codiFolio
     *
     * @return integer 
     */
    public function getCodiFolio() {
        return $this->codiFolio;
    }

    /**
     * Set periodoFolio
     *
     * @param string $periodoFolio
     * @return Folios
     */
    public function setPeriodoFolio($periodoFolio) {
        $this->periodoFolio = $periodoFolio;

        return $this;
    }

    /**
     * Get periodoFolio
     *
     * @return string 
     */
    public function getPeriodoFolio() {
        return $this->periodoFolio;
    }

    /**
     * Set registrosFolio
     *
     * @param integer $registrosFolio
     * @return Folios
     */
    public function setRegistrosFolio($registrosFolio) {
        $this->registrosFolio = $registrosFolio;

        return $this;
    }

    /**
     * Get registrosFolio
     *
     * @return integer 
     */
    public function getRegistrosFolio() {
        return $this->registrosFolio;
    }

    /**
     * Set tipoPlanTpl
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\TPlanilla $tipoPlanTpl
     * @return Folios
     */
    public function setTipoPlanTpl(\Inei\Bundle\PayrollBundle\Entity\TPlanilla $tipoPlanTpl = null) {
        $this->tipoPlanTpl = $tipoPlanTpl;

        return $this;
    }

    /**
     * Get tipoPlanTpl
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\TPlanilla 
     */
    public function getTipoPlanTpl() {
        return $this->tipoPlanTpl;
    }

    /**
     * Set tomo
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Tomos $tomo
     * @return Folios
     */
    public function setTomo(\Inei\Bundle\PayrollBundle\Entity\Tomos $tomo = null) {
        $this->tomo = $tomo;

        return $this;
    }

    /**
     * Get tomo
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Tomos 
     */
    public function getTomo() {
        return $this->tomo;
    }

    /**
     * Set subtPlanStp
     *
     * @param string $subtPlanStp
     * @return Folios
     */
    public function setSubtPlanStp($subtPlanStp) {
        $this->subtPlanStp = $subtPlanStp;

        return $this;
    }

    /**
     * Get subtPlanStp
     *
     * @return string 
     */
    public function getSubtPlanStp() {
        return $this->subtPlanStp;
    }

    /**
     * Add conceptos
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos
     * @return Folios
     */
    public function addConcepto(\Inei\Bundle\PayrollBundle\Entity\ConceptosFolios $conceptos) {
        //echo $conceptos->getCodiConcTco();
        //if(!$this->conceptos->contains($conceptos)){
        $this->conceptos[] = $conceptos;
        $conceptos->setCodiFolio($this);
        //}
        //return $this;
    }

    /**
     * Remove conceptos
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos
     */
    public function removeConcepto(\Inei\Bundle\PayrollBundle\Entity\ConceptosFolios $conceptos) {
        //$this->conceptos->removeElement($conceptos);
        //echo $this->rmconceptos;
        $this->rmconceptos[] = $conceptos;
    }

    /**
     * Remove conceptos
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos
     */
    public function getConcepto($pk) {
        foreach ($this->getConceptos() as $concepto) {
            if ($concepto->getCodiConcTco()->getCodiConcTco() === $pk) {
                return $concepto->getCodiConcTco();
                break;
            }
        }
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConceptos() {
        return $this->conceptos;
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRmconceptos() {
        return $this->rmconceptos;
    }

    /**
     * Set codiFolio
     *
     * @param integer $codiFolio
     * @return Folios
     */
    public function setCodiFolio($codiFolio) {
        $this->codiFolio = $codiFolio;

        return $this;
    }

    /**
     * Set folio
     *
     * @param integer $folio
     * @return Folios
     */
    public function setFolio($folio) {
        $this->folio = $folio;

        return $this;
    }

    /**
     * Get folio
     *
     * @return integer 
     */
    public function getFolio() {
        return $this->folio;
    }

    /**
     * Add planillas
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas $planillas
     * @return Folios
     */
    public function addPlanilla(\Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas $planillas) {
        $this->planillas[] = $planillas;
        $planillas->setFolio($this);
        return $this;
    }

    /**
     * Remove planillas
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas $planillas
     */
    public function removePlanilla(\Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas $planillas) {
        $this->planillas->removeElement($planillas);
    }

    /**
     * Get planillas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlanillas($em) {
        //return $this->planillas;
        if (null === $this->planillas) {
            $qb = $em->createQueryBuilder();
            $qb->select('c')
                    ->from('IneiPayrollBundle:PlanillaHistoricas', 'c')
                    ->where('c.folio = :folio')
                    ->orderBy('c.id', 'ASC')
                    ->setParameter('folio', $this->codiFolio);
            $this->planillas = $qb->getQuery()->getResult();
        }
        return $this->planillas;
    }

    public function getPayrolls() {
        if ($this->getPlanillas()->count() > 0) {
            return $this->getPlanillas();
        }
        return array_map(
                create_function('$item', 'return array();'), range(1, $this->getRegistrosFolio()));
    }

    public function setConceptos($conceptos) {
        return $this->conceptos = $conceptos;
    }

    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Folios
     */
    public function setFecCreac($fecCreac) {
        $this->fec_creac = $fecCreac;

        return $this;
    }

    /**
     * Get fec_creac
     *
     * @return \DateTime 
     */
    public function getFecCreac() {
        return $this->fec_creac;
    }

    /**
     * Set fec_mod
     *
     * @param \DateTime $fecMod
     * @return Folios
     */
    public function setFecMod($fecMod) {
        $this->fec_mod = $fecMod;

        return $this;
    }

    /**
     * Get fec_mod
     *
     * @return \DateTime 
     */
    public function getFecMod() {
        return $this->fec_mod;
    }

    /**
     * Set creador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $creador
     * @return Folios
     */
    public function setCreador(\Inei\Bundle\AuthBundle\Entity\Usuarios $creador = null) {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get creador
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getCreador() {
        return $this->creador;
    }

    /**
     * Set modificador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $modificador
     * @return Folios
     */
    public function setModificador(\Inei\Bundle\AuthBundle\Entity\Usuarios $modificador = null) {
        $this->modificador = $modificador;

        return $this;
    }

    /**
     * Get modificador
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getModificador() {
        return $this->modificador;
    }

}
