<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tplanilla
 *
 * @ORM\Table(name="TPLANILLA")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\TplanillaRepository")
 */
class Tplanilla
{
    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_PLAN_TPL", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $tipoPlanTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="DESC_TIPO_TPL", type="string", length=100, nullable=false)
     */
    private $descTipoTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="TARJ_INIC_TPL", type="string", length=4, nullable=true)
     */
    private $tarjInicTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="TARJ_FINA_TPL", type="string", length=4, nullable=true)
     */
    private $tarjFinaTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CANT_PERI_TPL", type="string", length=3, nullable=true)
     */
    private $cantPeriTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_OPER_OPE", type="string", length=9, nullable=true)
     */
    private $codiOperOpe;

    /**
     * @var string
     *
     * @ORM\Column(name="ABREV_TIPO_TPL", type="string", length=10, nullable=true)
     */
    private $abrevTipoTpl;
    
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
        return $this->descTipoTpl;
    }
    
    /**
     * Get tipoPlanTpl
     *
     * @return string 
     */
    public function setTipoPlanTpl($tipoPlanTpl)
    {
       $this->tipoPlanTpl = $tipoPlanTpl;
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
     * Set descTipoTpl
     *
     * @param string $descTipoTpl
     * @return Tplanilla
     */
    public function setDescTipoTpl($descTipoTpl)
    {
        $this->descTipoTpl = $descTipoTpl;
    
        return $this;
    }

    /**
     * Get descTipoTpl
     *
     * @return string 
     */
    public function getDescTipoTpl()
    {
        return $this->descTipoTpl;
    }

    /**
     * Set tarjInicTpl
     *
     * @param string $tarjInicTpl
     * @return Tplanilla
     */
    public function setTarjInicTpl($tarjInicTpl)
    {
        $this->tarjInicTpl = $tarjInicTpl;
    
        return $this;
    }

    /**
     * Get tarjInicTpl
     *
     * @return string 
     */
    public function getTarjInicTpl()
    {
        return $this->tarjInicTpl;
    }

    /**
     * Set tarjFinaTpl
     *
     * @param string $tarjFinaTpl
     * @return Tplanilla
     */
    public function setTarjFinaTpl($tarjFinaTpl)
    {
        $this->tarjFinaTpl = $tarjFinaTpl;
    
        return $this;
    }

    /**
     * Get tarjFinaTpl
     *
     * @return string 
     */
    public function getTarjFinaTpl()
    {
        return $this->tarjFinaTpl;
    }

    /**
     * Set cantPeriTpl
     *
     * @param string $cantPeriTpl
     * @return Tplanilla
     */
    public function setCantPeriTpl($cantPeriTpl)
    {
        $this->cantPeriTpl = $cantPeriTpl;
    
        return $this;
    }

    /**
     * Get cantPeriTpl
     *
     * @return string 
     */
    public function getCantPeriTpl()
    {
        return $this->cantPeriTpl;
    }

    /**
     * Set codiOperOpe
     *
     * @param string $codiOperOpe
     * @return Tplanilla
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
     * Set abrevTipoTpl
     *
     * @param string $abrevTipoTpl
     * @return Tplanilla
     */
    public function setAbrevTipoTpl($abrevTipoTpl)
    {
        $this->abrevTipoTpl = $abrevTipoTpl;
    
        return $this;
    }

    /**
     * Get abrevTipoTpl
     *
     * @return string 
     */
    public function getAbrevTipoTpl()
    {
        return $this->abrevTipoTpl;
    }

    /**
     * Set fec_creac
     *
     * @param \DateTime $fecCreac
     * @return Tplanilla
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
     * @return Tplanilla
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
     * @return Tplanilla
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
     * @return Tplanilla
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
