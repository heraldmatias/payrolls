<?php

namespace Inei\Bundle\ConsistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personal Digitado
 *
 * @ORM\Table(name="personal_digitado",
 * indexes={@ORM\Index(name="CODI_EMPL_PER_dig_idx", columns={"CODI_EMPL_PER"}),
 * @ORM\Index(name="NOMB_CORT_PER_idx", columns={"NOMB_CORT_PER"})})
 * @ORM\Entity(repositoryClass="Inei\Bundle\ConsistenciaBundle\Repository\PersonalDigitadoRepository")
 */
class PersonalDigitado
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="CODI_EMPL_PER", type="string", length=8, nullable=true)     
     */
    private $codiEmplPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_CORT_PER", type="string", length=150, nullable=false)
     */
    private $nombCortPer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_SOUNDEX_PER", type="string", length=8, nullable=true)
     */
    private $soundex;
    
    /**
     * @var \Inei\Bundle\ConsistenciaBundle\Entity\PersonalEncontrado
     * @ORM\ManyToOne(targetEntity="PersonalEncontrado", inversedBy="personas")
     * @ORM\JoinColumn(name="CODI_EMPL_PER_PERSONA", referencedColumnName="CODI_EMPL_PER", nullable=true)
     */
    private $persona;

    /**
     * Set codiEmplPer
     *
     * @param string $codiEmplPer
     * @return PersonalDigitado
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
     * @return PersonalDigitado
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
     * Set persona
     *
     * @param \Inei\Bundle\ConsistenciaBundle\Entity\PersonalEncontrado $persona
     * @return PersonalDigitado
     */
    public function setPersona(\Inei\Bundle\ConsistenciaBundle\Entity\PersonalEncontrado $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \Inei\Bundle\ConsistenciaBundle\Entity\PersonalEncontrado 
     */
    public function getPersona()
    {
        return $this->persona;
    }

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
     * Set soundex
     *
     * @param string $soundex
     * @return PersonalDigitado
     */
    public function setSoundex($soundex)
    {
        $this->soundex = $soundex;

        return $this;
    }

    /**
     * Get soundex
     *
     * @return string 
     */
    public function getSoundex()
    {
        return $this->soundex;
    }
}
