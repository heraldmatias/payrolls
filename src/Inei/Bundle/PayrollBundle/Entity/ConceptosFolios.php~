<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Tplanilla
 *
 * @ORM\Table(name="CONCEPTOS_FOLIOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\ConceptosFoliosRepository")
 */
class ConceptosFolios
{       
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;
    
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Folios", inversedBy="conceptos", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="CODI_FOLIO", referencedColumnName="CODI_FOLIO", nullable=true)
     */
    private $codiFolio;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Conceptos", inversedBy="folios", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="CODI_CONC_TCO", referencedColumnName="CODI_CONC_TCO", nullable=true)
     */
    private $codiConcTco;

    /**
     * @var integer
     * 
     * @ORM\Column(name="ORDEN_CONC_FOLIO", type="integer", nullable=false)
     */
    private $orden;
    
    public function __toString() {
        return $this->getCodiConcTco();
    }

    /**
     * Set orden
     *
     * @param integer $orden
     * @return ConceptosFolios
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set codiFolio
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $codiFolio
     * @return ConceptosFolios
     */
    public function setCodiFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $codiFolio)
    {
        $this->codiFolio = $codiFolio;

        return $this;
    }

    /**
     * Get codiFolio
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Folios 
     */
    public function getCodiFolio()
    {
        return $this->codiFolio;
    }

    /**
     * Set codiConcTco
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $codiConcTco
     * @return ConceptosFolios
     */
    public function setCodiConcTco(\Inei\Bundle\PayrollBundle\Entity\Conceptos $codiConcTco)
    {
        $this->codiConcTco = $codiConcTco;

        return $this;
    }

    /**
     * Get codiConcTco
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Conceptos 
     */
    public function getCodiConcTco()
    {
        return $this->codiConcTco;
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
}
