<?php
namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of ConceptoFolio
 *
 * @author holivares
 */
/**
 * Folios
 *
 * @ORM\Table(name="FOLIOS_CONCEPTOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\FoliosConceptosRepository")
 */
class ConceptoFolio {
    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Folios", inversedBy="folios")
     * @ORM\JoinColumn(name="CODI_FOLIO", referencedColumnName="CODI_FOLIO", nullable=false)
     */
    private $codiFolio;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Conceptos
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Conceptos", inversedBy="conceptos")
     * @ORM\JoinColumn(name="CODI_CONC_TCO", referencedColumnName="CODI_CONC_TCO")
     */
    private $codiConcTco;

    /**
     * Set codiFolio
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $codiFolio
     * @return ConceptoFolio
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
     * @return ConceptoFolio
     */
    public function setCodiConcTco(\Inei\Bundle\PayrollBundle\Entity\Conceptos $codiConcTco = null)
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
}
