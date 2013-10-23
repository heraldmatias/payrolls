<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ConceptosFoliosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConceptosFoliosRepository extends EntityRepository
{
    public function getForColumns($folio){
        $query = $this->getEntityManager()
                ->createQuery('SELECT c.descCortTco
                    FROM IneiPayrollBundle:Conceptos c
                    JOIN c.folios cf
                    WHERE cf.codiFolio = :folio
                    ORDER BY cf.orden ASC')
                ->setParameter('folio', $folio);
        return $query->getArrayResult();
    }
}
