<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tplanilla
 *
 * @ORM\Table(name="TPLANILLA")
 * @ORM\Entity
 */
class Tplanilla
{
    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_PLAN_TPL", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TPLANILLA_TIPO_PLAN_TPL_seq", allocationSize=1, initialValue=1)
     */
    private $tipoPlanTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="DESC_TIPO_TPL", type="string", length=30, nullable=false)
     */
    private $descTipoTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="TARJ_INIC_TPL", type="string", length=4, nullable=false)
     */
    private $tarjInicTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="TARJ_FINA_TPL", type="string", length=4, nullable=false)
     */
    private $tarjFinaTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CANT_PERI_TPL", type="string", length=3, nullable=false)
     */
    private $cantPeriTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_OPER_OPE", type="string", length=9, nullable=false)
     */
    private $codiOperOpe;

    /**
     * @var string
     *
     * @ORM\Column(name="ABREV_TIPO_TPL", type="string", length=10, nullable=false)
     */
    private $abrevTipoTpl;



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
}