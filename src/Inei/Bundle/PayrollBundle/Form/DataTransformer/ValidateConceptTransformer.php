<?php
/**
 * Description of ValidateConceptTransformer
 *
 * @author holivares
 */
namespace Inei\Bundle\PayrollBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Inei\Bundle\PayrollBundle\Entity\Conceptos;

class ValidateConceptTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Conceptos|null $concept
     * @return string
     */
    public function transform($concepts)
    {
        if (null === $concepts) {
            return null;
        }
        /*if ($concept instanceof \Doctrine\ORM\PersistentCollection){
            return null;
        }*/
//        $p = new \Doctrine\ORM\PersistentCollection();
//        $p->set($key, $value);
//        $p->removeElement($element);
        return $concepts;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $codigo
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($concepts)
    {
        if (!$concepts) {
            return null;
        }
        $toremove = array();
        $topersist = array();
        /*SI ES UNA NUEVA COLLECCION DE CONCEPTOS*/
        if(!$concepts instanceof \Doctrine\ORM\PersistentCollection){
            foreach ($concepts as $key => $concept) {
                $_concept = $this->om
                ->getRepository('IneiPayrollBundle:Conceptos')
                ->findOneBy(array('codiConcTco' => $concept->getCodiConcTco()));
                if($_concept){
                    $topersist[] = $_concept;
                }
            }
            return $topersist;
        }
        /*SI ES QUE ES UNA ACTUALIZACION DE LOS CONCEPTOS*/
        foreach ($concepts as $key => $concept) {
            $_concept = $this->om
            ->getRepository('IneiPayrollBundle:Conceptos')
            ->findOneBy(array('codiConcTco' => $concept->getCodiConcTco()));
            if(!$_concept){
                $toremove[] = $concept;
            }else{
                $topersist[] = $_concept;
            }
        }        
//        foreach ($toremove as $key => $value) {            
//            $concepts->removeElement($value);            
//        }
        $concepts->clear();
        foreach ($topersist as $key => $value) {         
            $concepts->set($key, $value);
        }        
        return $concepts;

    }
}