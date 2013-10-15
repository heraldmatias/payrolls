<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FoliosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FoliosRepository extends EntityRepository {

    public function findCustomBy($criteria) {
        $DQL = "
            SELECT t.codiTomo as tomo, f.codiFolio, f.folio, pla.descTipoTpl, f.periodoFolio, f.registrosFolio, f.subtPlanStp
            FROM IneiPayrollBundle:Folios f
            JOIN f.tomo t
            LEFT JOIN f.tipoPlanTpl pla
        ";
        $where = array();
        if (array_key_exists('tomo', $criteria))
            $where[] = 't.codiTomo =' . $criteria['tomo'];
        if (array_key_exists('folio', $criteria))
            $where[] = 'f.folio =' . $criteria['folio'];
        if (array_key_exists('periodoFolio', $criteria))
            $where[] = "f.periodoFolio ='" . $criteria['periodoFolio'] . "'";
        if (array_key_exists('registrosFolio', $criteria))
            $where[] = 'f.registrosFolio =' . $criteria['registrosFolio'];
        $DQL .= count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';
        $qb = $this->getEntityManager()->createQuery($DQL);
        return $qb->getResult();
    }

    public function findOneCustomBy($pk) {
        $DQL = "SELECT f, t, pla
            FROM IneiPayrollBundle:Folios f
            JOIN f.tomo t
            LEFT JOIN f.tipoPlanTpl pla WHERE f.codiFolio = :pk";
        $qb = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'pk' => $pk,
        ));
        $re = $qb->getResult();
        if(!$re)
            return null;
        $folio = $re[0];

        /*         * *CONCEPTOS*** */
        $DQL = "SELECT cf, c
            FROM IneiPayrollBundle:ConceptosFolios cf
            JOIN cf.codiConcTco c
            WHERE cf.codiFolio = :pk ORDER BY cf.orden";
        $qbc = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'pk' => $pk,
        ));
        $folio->setConceptos($qbc->getResult());
//        foreach ($qbc as $key => $conc){
//            $folio->getConceptos()->set($key, $conc);
//        }
        return $folio;
    }

    public function findOneCustomByNum($folio, $tomo) {
        $DQL = "SELECT f, t, pla
            FROM IneiPayrollBundle:Folios f
            JOIN f.tomo t
            LEFT JOIN f.tipoPlanTpl pla WHERE f.folio = :folio AND f.tomo = :tomo";
        $qb = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'folio' => is_numeric($folio)?$folio:0,
            'tomo' => is_numeric($tomo)?$tomo:0
        ));
        $re = $qb->getResult();
        if(!$re)
            return null;
        $folio = $re[0];
        /*         * *CONCEPTOS*** */
        $DQL = "SELECT cf, c
            FROM IneiPayrollBundle:ConceptosFolios cf
            JOIN cf.codiConcTco c
            WHERE cf.codiFolio = :pk ORDER BY cf.orden";
        $qbc = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'pk' => $folio->getCodiFolio(),
        ));
        $folio->setConceptos($qbc->getResult());
//        foreach ($qbc as $key => $conc){
//            $folio->getConceptos()->set($key, $conc);
//        }
        return $folio;
    }

}
