<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
/**
 * Folios
 *
 * @ORM\Table(name="FOLIOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\FoliosRepository")
 * @HasLifecycleCallbacks
 */

class Folios
{        
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @ORM\ManyToOne(targetEntity="Tomos", inversedBy="setfolios")
     * @ORM\JoinColumn(name="CODI_TOMO", referencedColumnName="CODI_TOMO", nullable=false)
     */
    private $tomo;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\tplanilla
     * @ORM\ManyToOne(targetEntity="Tplanilla", inversedBy="plafolios")
     * @ORM\JoinColumn(name="TIPO_PLAN_TPL", referencedColumnName="TIPO_PLAN_TPL")
     */
    private $tipoPlanTpl;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\subtplanilla     
     * @ORM\Column(name="SUBT_PLAN_STP", type="string", length=2, nullable=false)     
     */
    private $subtPlanStp;

    /**
     * Bidirectional - muchas planillas tienen muchos conceptos (OWNING SIDE)
     *
     * @ORM\ManyToMany(targetEntity="Conceptos", inversedBy="folios", cascade={"persist"})
     * @ORM\JoinTable(name="folios_conceptos",
     *   joinColumns={@ORM\JoinColumn(name="CODI_FOLIO", referencedColumnName="CODI_FOLIO")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="CODI_CONC_TCO", referencedColumnName="CODI_CONC_TCO")}
     * )
     */
    private $conceptos;

    public function __construct()
    {
        $this->conceptos = new ArrayCollection();
    }
    
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

    

    /**
     * Set subtPlanStp
     *
     * @param string $subtPlanStp
     * @return Folios
     */
    public function setSubtPlanStp($subtPlanStp)
    {
        $this->subtPlanStp = $subtPlanStp;
    
        return $this;
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
     * Add conceptos
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos
     * @return Folios
     */
    public function addConcepto(\Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos)
    {
        echo $conceptos->getCodiConcTco();
        if(!$this->conceptos->contains($conceptos)){
            $this->conceptos[] = $conceptos;
        }
        return $this;
    }

    /**
     * Remove conceptos
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos
     */
    public function removeConcepto(\Inei\Bundle\PayrollBundle\Entity\Conceptos $conceptos)
    {
        $this->conceptos->removeElement($conceptos);
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConceptos()
    {
        return $this->conceptos;
    }
}