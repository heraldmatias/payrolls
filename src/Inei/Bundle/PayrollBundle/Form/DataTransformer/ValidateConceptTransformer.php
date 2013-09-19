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

class ValidateConceptTransformer implements DataTransformerInterface {

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om) {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Conceptos|null $concept
     * @return string
     */
    public function transform($concepts) {
        if (null === $concepts) {
            return null;
        }
        /* if ($concept instanceof \Doctrine\ORM\PersistentCollection){
          return null;
          } */
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
    public function reverseTransform($concepts) {
        if (!$concepts) {
            return null;
        }
        $topersist = array();
        /* SI ES QUE ES UNA ACTUALIZACION DE LOS CONCEPTOS */
        foreach ($concepts as $concept) {
            $topersist[] = $concept->getCodiConcTco();
        }
        $_topersist = $this->getQueryConceptos($topersist)->getResult();
        return $_topersist;
    }
    
    private function getQueryConceptos($codigos){
        $qb = $this->om->createQueryBuilder();
        $qb->select('c')
                ->from('IneiPayrollBundle:Conceptos', 'c')
                ->where('c.codiConcTco IN (:lista)')
                ->orderBy('c.codiConcTco', 'ASC')
                ->setParameter('lista', $codigos);
        $query = $qb->getQuery();
        return $query;
    }

}