<?php

namespace Inei\Bundle\PayrollBundle\Service;

use Doctrine\ORM\EntityManager;


/**
 * Description of TomosService
 *
 * @author holivares
 */
class TomosService {
    private $em;    

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function findNoDigitado($tomo){
        return $this->em->getRepository('IneiPayrollBundle:Tomos')
                ->findNoDigitado($tomo);
    }
    
    public function findTomosAsignados($usuario){
        return $this->em->getRepository('IneiPayrollBundle:Tomos')
                ->findAsignados($usuario);
    }
}