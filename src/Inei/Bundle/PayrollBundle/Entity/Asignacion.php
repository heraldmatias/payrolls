<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Asignacion
 *
 * @author holivares
 */
/**
 * Asignacion
 *
 * @ORM\Table(name="asignacion")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\AsignacionRepository")
 */

class Asignacion {
    
    /**
     * @var Inei\Bundle\AuthBundle\Entity\Usuarios
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Inei\Bundle\AuthBundle\Entity\Usuarios")
     * @ORM\JoinColumn(name="CO_ASIGNADO", referencedColumnName="id", nullable=false)
     */
    private $asignado;
    
    /**
     * @var Inei\Bundle\PayrollBundle\Entity\Tomos
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tomos")
     * @ORM\JoinColumn(name="CO_TOMO", referencedColumnName="CODI_TOMO", nullable=false)
     */
    private $tomo;
    
    /**
     * @var Inei\Bundle\AuthBundle\Entity\Usuarios
     * @ORM\ManyToOne(targetEntity="Inei\Bundle\AuthBundle\Entity\Usuarios")
     * @ORM\JoinColumn(name="CO_ASIGNADOR", referencedColumnName="id", nullable=false)
     */
    private $asignador;
    
    /**
     * @var Inei\Bundle\AuthBundle\Entity\Usuarios
     * @ORM\ManyToOne(targetEntity="Inei\Bundle\AuthBundle\Entity\Usuarios")
     * @ORM\JoinColumn(name="CO_MODIFICADOR", referencedColumnName="id", nullable=true)
     */
    private $modificador;
    
    /**
     * @var datetime
     * @ORM\Column(name="FE_ASIGNACION", type="datetime", nullable=true)
     */
    private $fechaAsignacion;
    
    /**
     * @var datetime
     * @ORM\Column(name="FE_MODIFICA_ASIG", type="datetime", nullable=true)
     */
    private $fechaModificacion;
    
    /**
     * Set fechaAsignacion
     *
     * @param \DateTime $fechaAsignacion
     * @return Asignacion
     */
    public function setFechaAsignacion($fechaAsignacion)
    {
        $this->fechaAsignacion = $fechaAsignacion;

        return $this;
    }

    /**
     * Get fechaAsignacion
     *
     * @return \DateTime 
     */
    public function getFechaAsignacion()
    {
        return $this->fechaAsignacion;
    }

    /**
     * Set fechaModificacion
     *
     * @param \DateTime $fechaModificacion
     * @return Asignacion
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return \DateTime 
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set asignado
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $asignado
     * @return Asignacion
     */
    public function setAsignado(\Inei\Bundle\AuthBundle\Entity\Usuarios $asignado)
    {
        $this->asignado = $asignado;

        return $this;
    }

    /**
     * Get asignado
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getAsignado()
    {
        return $this->asignado;
    }

    /**
     * Set tomo
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Tomos $tomo
     * @return Asignacion
     */
    public function setTomo(\Inei\Bundle\PayrollBundle\Entity\Tomos $tomo)
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
     * Set asignador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $asignador
     * @return Asignacion
     */
    public function setAsignador(\Inei\Bundle\AuthBundle\Entity\Usuarios $asignador)
    {
        $this->asignador = $asignador;

        return $this;
    }

    /**
     * Get asignador
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Usuarios 
     */
    public function getAsignador()
    {
        return $this->asignador;
    }

    /**
     * Set modificador
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $modificador
     * @return Asignacion
     */
    public function setModificador(\Inei\Bundle\AuthBundle\Entity\Usuarios $modificador)
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
