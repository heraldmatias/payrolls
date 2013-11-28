<?php

namespace Inei\Bundle\ConsistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personal Digitado
 *
 * @ORM\Table(name="personal_encontrado")
 * @ORM\Entity(repositoryClass="Inei\Bundle\ConsistenciaBundle\Repository\PersonalEncontradoRepository")
 */
class PersonalEncontrado
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODI_EMPL_PER", type="string", length=8, nullable=false)
     * @ORM\Id
     */
    private $codiEmplPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APE_PAT_PER", type="string", length=35, nullable=false)
     */
    private $apePatPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APE_MAT_PER", type="string", length=35, nullable=false)
     */
    private $apeMatPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_EMP_PER", type="string", length=35, nullable=false)
     */
    private $nomEmpPer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_CORT_PER", type="string", length=150, nullable=false)
     */
    private $nombCortPer;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBR_ELEC_PER", type="string", length=8, nullable=true)
     */
    private $librElecPer;
    
    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\PersonalDigitado
     * @ORM\OneToMany(targetEntity="PersonalDigitado", mappedBy="persona", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $personas;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->codiEmplPer;
    }
    /**
     * Set codiEmplPer
     *
     * @param string $codiEmplPer
     * @return PersonalEncontrado
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
     * Set nombCortPer
     *
     * @param string $nombCortPer
     * @return PersonalEncontrado
     */
    public function setNombCortPer($nombCortPer)
    {
        $this->nombCortPer = $nombCortPer;

        return $this;
    }

    /**
     * Get nombCortPer
     *
     * @return string 
     */
    public function getNombCortPer()
    {
        return $this->nombCortPer;
    }

    /**
     * Set librElecPer
     *
     * @param string $librElecPer
     * @return PersonalEncontrado
     */
    public function setLibrElecPer($librElecPer)
    {
        $this->librElecPer = $librElecPer;

        return $this;
    }

    /**
     * Get librElecPer
     *
     * @return string 
     */
    public function getLibrElecPer()
    {
        return $this->librElecPer;
    }

    /**
     * Add personas
     *
     * @param \Inei\Bundle\ConsistenciaBundle\Entity\PersonalDigitado $personas
     * @return PersonalEncontrado
     */
    public function addPersona(\Inei\Bundle\ConsistenciaBundle\Entity\PersonalDigitado $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \Inei\Bundle\ConsistenciaBundle\Entity\PersonalDigitado $personas
     */
    public function removePersona(\Inei\Bundle\ConsistenciaBundle\Entity\PersonalDigitado $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Set apePatPer
     *
     * @param string $apePatPer
     * @return PersonalEncontrado
     */
    public function setApePatPer($apePatPer)
    {
        $this->apePatPer = $apePatPer;

        return $this;
    }

    /**
     * Get apePatPer
     *
     * @return string 
     */
    public function getApePatPer()
    {
        return $this->apePatPer;
    }

    /**
     * Set apeMatPer
     *
     * @param string $apeMatPer
     * @return PersonalEncontrado
     */
    public function setApeMatPer($apeMatPer)
    {
        $this->apeMatPer = $apeMatPer;

        return $this;
    }

    /**
     * Get apeMatPer
     *
     * @return string 
     */
    public function getApeMatPer()
    {
        return $this->apeMatPer;
    }

    /**
     * Set nomEmpPer
     *
     * @param string $nomEmpPer
     * @return PersonalEncontrado
     */
    public function setNomEmpPer($nomEmpPer)
    {
        $this->nomEmpPer = $nomEmpPer;

        return $this;
    }

    /**
     * Get nomEmpPer
     *
     * @return string 
     */
    public function getNomEmpPer()
    {
        return $this->nomEmpPer;
    }
}
