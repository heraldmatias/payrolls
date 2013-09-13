<?php
namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of FoliosConceptos
 *
 * @author holivares
 */


/**
 * Folios
 *
 * @ORM\Table(name="FOLIOS_CONCEPTOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\FoliosConceptosRepository")
 */
class FoliosConceptos {
    
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="COD_FOL_CONC", type="integer", nullable=false)
     */
    private $codiFolConc;
/**
     * @var \Inei\Bundle\PayrollBundle\Entity\Folios
     * @ORM\ManyToOne(targetEntity="Folios", inversedBy="campos")
     * @ORM\JoinColumn(name="CODI_FOLIO", referencedColumnName="CODI_FOLIO")
     */
    private $codiFolio;

    /**
     * @var \Inei\Bundle\PayrollBundle\Entity\Conceptos
     * @ORM\ManyToOne(targetEntity="Conceptos", inversedBy="folios")
     * @ORM\JoinColumn(name="CODI_CONC_TCO", referencedColumnName="CODI_CONC_TCO")     
     */
    private $codConcepto;

    /**
     * Set codiFolio
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Folios $codiFolio
     * @return FoliosConceptos
     */
    public function setCodiFolio(\Inei\Bundle\PayrollBundle\Entity\Folios $codiFolio = null)
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
     * Set codConcepto
     *
     * @param \Inei\Bundle\PayrollBundle\Entity\Conceptos $codConcepto
     * @return FoliosConceptos
     */
    public function setCodConcepto(\Inei\Bundle\PayrollBundle\Entity\Conceptos $codConcepto = null)
    {
        $this->codConcepto = $codConcepto;
    
        return $this;
    }

    /**
     * Get codConcepto
     *
     * @return \Inei\Bundle\PayrollBundle\Entity\Conceptos 
     */
    public function getCodConcepto()
    {
        return $this->codConcepto;
    }

    /**
     * Get codiFolConc
     *
     * @return integer 
     */
    public function getCodiFolConc()
    {
        return $this->codiFolConc;
    }
}