<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Tplanilla
 *
 * @ORM\Table(name="TOMOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\TomosRepository")
 */
class Tomos
{    
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="CODI_TOMO", type="integer", nullable=false)
     */
    private $codiTomo;

    /**
     * @var string
     * 
     * @ORM\Column(name="PER_TOMO", type="string", length=100, nullable=false)
     */
    private $periodoTomo;

    /**
     * @var integer
     * 
     * @ORM\Column(name="ANO_TOMO", type="string", length=30, nullable=false)
     */
    private $anoTomo;

    /**
     * @var integer
     * 
     * @ORM\Column(name="FOLIOS_TOMO", type="integer", nullable=false)
     */
    private $foliosTomo;

    /**
     * @var string
     * 
     * @ORM\Column(name="DESC_TOMO", type="text", nullable=true)
     */
    private $descTomo;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios
     * @ORM\OneToMany(targetEntity="Folios", mappedBy="tomo", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
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

    public function __toString() {
        return 'TOMO - '.$this->codiTomo;
    }
    
    public function __construct()
    {
        $this->folios = new ArrayCollection();
    }

    /*public function addFolio(Folios $folio)
    {
        $this->folios->add($folio);
    }

    public function removeFolio(Folios $folio)
    {
        
    }*/
    
    /**
     * Set codiTomo
     *
     * @param integer $codiTomo
     * @return Tomos
     */
    public function setCodiTomo($codiTomo)
    {
        $this->codiTomo = $codiTomo;
    
        return $this;
    }

    /**
     * Get codiTomo
     *
     * @return integer 
     */
    public function getCodiTomo()
    {
        return $this->codiTomo;
    }

    /**
     * Set periodoTomo
     *
     * @param string $periodoTomo
     * @return Tomos
     */
    public function setPeriodoTomo($periodoTomo)
    {
        $this->periodoTomo = $periodoTomo;
    
        return $this;
    }

    /**
     * Get periodoTomo
     *
     * @return string 
     */
    public function getPeriodoTomo()
    {
        return $this->periodoTomo;
    }

    /**
     * Set anoTomo
     *
     * @param integer $anoTomo
     * @return Tomos
     */
    public function setAnoTomo($anoTomo)
    {
        $this->anoTomo = $anoTomo;
    
        return $this;
    }

    /**
     * Get anoTomo
     *
     * @return integer 
     */
    public function getAnoTomo()
    {
        return $this->anoTomo;
    }

    /**
     * Set foliosTomo
     *
     * @param integer $foliosTomo
     * @return Tomos
     */
    public function setFoliosTomo($foliosTomo)
    {
        $this->foliosTomo = $foliosTomo;
    
        return $this;
    }

    /**
     * Get foliosTomo
     *
     * @return integer 
     */
    public function getFoliosTomo()
    {
        return $this->foliosTomo;
    }

    /**
     * Set descTomo
     *
     * @param string $descTomo
     * @return Tomos
     */
    public function setDescTomo($descTomo)
    {
        $this->descTomo = $descTomo;
    
        return $this;
    }

    /**
     * Get descTomo
     *
     * @return string 
     */
    public function getDescTomo()
    {
        return $this->descTomo;
    }

    /**
     * Add folios
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $folios
     * @return Tomos
     */
    public function addFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $folio)
    {
        /*$this->folios[] = $folios;
    
        return $this;*/
        $folio->setTomo($this);
        $this->folios->add($folio);
    }

    /**
     * Remove folios
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $folios
     */
    public function removeFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $folios)
    {
        $this->folios->removeElement($folios);
    }

    /**
     * Get folios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFolios()
    {
        return $this->folios;
    }
    
    public function setFolios($folios)
    {
        return $this->folios = $folios;
    }

    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Tomos
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
     * @return Tomos
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
     * @return Tomos
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
     * @return Tomos
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
