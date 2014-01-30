<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * FoliosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FoliosRepository extends EntityRepository {

    public function findCustomBy($criteria) {
        $DQL = "
            SELECT partial f.{codiFolio, folio, periodoFolio, registrosFolio, subtPlanStp, fec_creac, fec_mod},pla.descTipoTpl,t.codiTomo as tomo, c.username as crea, m.username as mod
            FROM IneiPayrollBundle:Folios f
            JOIN f.tomo t
            LEFT JOIN f.creador c
            LEFT JOIN f.modificador m
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
        $qb = $this->getEntityManager()->createQuery($DQL.' ORDER BY f.folio');
        return $qb;
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
        $DQL = "SELECT cf, partial c.{codiConcTco, descConcTco, descCortTco}
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
        $DQL = "SELECT cf, partial c.{codiConcTco, descCortTco, tipoConcTco}
            FROM IneiPayrollBundle:ConceptosFolios cf
            JOIN cf.codiConcTco c
            WHERE cf.codiFolio = :pk ORDER BY cf.orden";
        $qbc = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'pk' => $folio->getCodiFolio(),
        ));
        $folio->setConceptos($qbc->getResult());
        return $folio;
    }
    
    public function findCustomByNum($folio, $tomo) {
        $DQL = "SELECT partial f.{codiFolio}
            FROM IneiPayrollBundle:Folios f
            WHERE f.folio = :folio AND f.tomo = :tomo";
        $qb = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'folio' => is_numeric($folio)?$folio:0,
            'tomo' => is_numeric($tomo)?$tomo:0
        ));
        $re = $qb->getArrayResult();
        if(!$re)
            return null;
        $folio = $re[0]['codiFolio'];
        /*         * *CONCEPTOS*** */
        $DQL = "SELECT partial cf.{id}, partial c.{codiConcTco}
            FROM IneiPayrollBundle:ConceptosFolios cf
            JOIN cf.codiConcTco c
            WHERE cf.codiFolio = :pk ORDER BY cf.orden";
        $qbc = $this->getEntityManager()->createQuery($DQL)
                ->setParameters(array(
            'pk' => $folio,
        ));        
        $lista = $qbc->getArrayResult();
        $conceptos['folio'] = $folio;
        $conceptos['conceptos'] = array_map(create_function('$item', 'return array($item["codiConcTco"]["codiConcTco"], $item["id"]);'), $lista);
        return $conceptos;
    }
    
    public function findFolioInventarioByNum($folio, $tomo) {
        /*folios*/
        $sql = "SELECT codi_folio, num_folio, per_folio, reg_folio, tipo_plan_tpl FROM folios WHERE codi_tomo = :tomo AND num_folio=:folio LIMIT 1;";
        $st = $this->getEntityManager()->getConnection()->prepare($sql);
        $st->bindValue(1, $tomo);
        $st->bindValue(2, $folio);
        $st->execute();
        $folio = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = null;
        /*conceptos*/
        if($folio){
            $sql = "SELECT codi_conc_tco FROM conceptos_folios WHERE codi_folio = :folio ORDER BY orden_conc_folio ASC;";
            $st2 = $this->getEntityManager()->getConnection()->prepare($sql);
            $st2->bindValue(1, $folio[0][0]);
            $st2->execute();
            unset($folio[0][0]);
            $_conceptos = $st2->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
            $conceptos = array_map(create_function('$item', 'return $item[0];'), $_conceptos);
            $data = array_merge($folio[0], $conceptos);
        }
        return $data;
    }

    public function findResumenFolios(array $filtro = null) {
        $where1 = array();
        $where2 = array();
        $where = array();
        $sql="select * from (select f.codi_tomo as tomo, pla.digitador, f.num_folio as folio, f.reg_folio as total_registros, pla.registros_digitados,
 pla.fecha, (case when f.reg_folio=pla.registros_digitados then 'COMPLETO' when f.reg_folio>pla.registros_digitados then 'INCOMPLETO' when pla.registros_digitados is null then 'POR DIGITAR' END) as estado from
(select p.codi_folio,u.cod_usu as digitador, count(distinct p.num_reg) as registros_digitados, max(fec_creac) as fecha
from planilla_historicas p
join usuarios u
on u.id = p.usu_crea_id %s
group by p.codi_folio,u.cod_usu) as pla
right join folios f
on f.codi_folio = pla.codi_folio
WHERE reg_folio is not null) as tabla %s order by folio ASC;";
        if ($filtro['fecha-ini'] & $filtro['fecha-fin']) {
            $where1[] = "date(p.fec_creac) between :fecini and :fecfin";
            $where['fecini'] = $filtro['fecha-ini'];
            $where['fecfin'] = $filtro['fecha-fin'];
        }

        if ($filtro['tomo']) {
            $where2[] = 'tomo = :tomo';
            $where['tomo'] = $filtro['tomo'];
        }
        if ($filtro['estado']) {
            $where2[] = "estado = :estado";
            $where['estado'] = $filtro['estado'];
        }
        $_where1 = count($where1) ? ' WHERE ' . implode(' AND ', $where1) : '';
        $_where2 = count($where2) ? ' WHERE ' . implode(' AND ', $where2) : '';
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('digitador', 'digitador');
        $rsm->addScalarResult('folio', 'folio');
        $rsm->addScalarResult('total_registros', 'total_registros');
        $rsm->addScalarResult('registros_digitados', 'registros_digitados');
        $rsm->addScalarResult('fecha', 'fecha');
        
        $rsm->addScalarResult('estado', 'estado');
        $query = $this->getEntityManager()->createNativeQuery(
                sprintf($sql, $_where1, $_where2), $rsm);
        $query->setParameters($where);
        $tomos = $query->getArrayResult();
        return $tomos;
    }
    
    public function findSiguienteFolioDigitable($tomo, $folio){
        $sql = "select * from lv_datos_folios where tomo= :tomo
            and folio>:folio limit 1;";
        echo $sql;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('tomo', $tomo);
        $stmt->bindValue('folio', $folio);
        $stmt->execute();
        return $stmt->fetch();
    }
}
