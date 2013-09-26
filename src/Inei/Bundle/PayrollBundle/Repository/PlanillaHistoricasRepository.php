<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PlanillaHistoricasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanillaHistoricasRepository extends EntityRepository
{
    public function getByFolio($folio){
        //$em = $this->getEntityManager();
        return $this->findBy(array(
            'folio' => $folio
        ));
    }
    
}
