<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SubtplanillaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubtplanillaRepository extends EntityRepository
{
    public function findUsingLike($filter = array(), $orderBy=NULL) {
        array_walk($filter, function(&$v, &$k) {
                    if ($k === 'descSubtStp') {
                        $v = " st.$k LIKE '%$v%'";
                    } else {
                        $v = "st.$k = $v";
                    }
                });
        $filter = count($filter) > 0 ? 'WHERE ' . implode(' AND ', $filter) : '';
        $query = $this->getEntityManager()
                ->createQuery('SELECT st, t
                    FROM IneiPayrollBundle:Subtplanilla st
                    JOIN st.tipoPlanTpl t ' . $filter.' '.$orderBy);
        return $query->getResult();
    }
}
