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
    
    public function findInfoTomo($tomo){
        return $this->em->getRepository('IneiPayrollBundle:Tomos')
                ->findTomo(is_numeric($tomo)?$tomo:0);
    }
    
    public function deleteTomo($tomo){
        $con = $this->em->getConnection();
        /**/        
        $del1 = "DELETE FROM planilla_historicas WHERE codi_folio IN 
            (SELECT codi_folio FROM folios WHERE codi_tomo = $tomo)";
        $del2 = "DELETE FROM conceptos_folios WHERE codi_folio IN 
            (SELECT codi_folio FROM folios WHERE codi_tomo = $tomo)";
        $del3 = "DELETE FROM folios WHERE codi_tomo = $tomo";
        $del4 = "DELETE FROM asignacion where co_tomo= $tomo";
        $del5 = "DELETE FROM tomos WHERE codi_tomo = $tomo";
        $con->executeUpdate($del1);
        $con->executeUpdate($del2);
        $con->executeUpdate($del3);
        $con->executeUpdate($del4);
        $con->executeUpdate($del5);
    }
    
}