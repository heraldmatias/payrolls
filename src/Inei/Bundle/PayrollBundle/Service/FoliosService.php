<?php

namespace Inei\Bundle\PayrollBundle\Service;

use Doctrine\ORM\EntityManager;
use Inei\Bundle\PayrollBundle\Entity\Folios;
/**
 * Description of FoliosService
 *
 * @author holivares
 */
class FoliosService {
   
    private $em;    

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function orderFolios($folio, $folioupdate, $tomo){
        echo $folio.'<br>'.$folioupdate;
        $conn = $this->em->getConnection();
        if (null === $folioupdate | trim($folioupdate)===''){
            $sql = "SELECT codi_folio FROM folios 
                WHERE codi_tomo=$tomo and num_folio=$folio";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $f = $stmt->fetch();
            if($f){
                $sql = "UPDATE folios 
                SET num_folio=num_folio+1 WHERE codi_tomo=$tomo and num_folio>=$folio ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
        }else if($folio != $folioupdate ){
            $sql = "SELECT codi_folio FROM folios 
                WHERE codi_tomo=$tomo and num_folio=$folio";
            $stmt1 = $conn->prepare($sql);
            $stmt1->execute();
            $result = $stmt1->fetch();
            $f = $result['codi_folio'];
//            $sql2 = "UPDATE folios 
//                SET num_folio=$folio WHERE codi_tomo=$tomo and num_folio=$folioupdate";
//            $conn->prepare($sql2)->execute();
            $sql3 = "UPDATE folios 
                SET num_folio=$folioupdate WHERE codi_folio = $f";
            $conn->prepare($sql3)->execute();
        }
        return true;
    }
    
    public function deletePlanillas($folio){
        $q = $this->em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas m where m.folio = ' . $folio);
        return $q->execute();
    }
}
