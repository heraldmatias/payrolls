<?php

namespace Inei\Bundle\PayrollBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * TomosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TomosRepository extends EntityRepository {

    public function findIncompletos() {
        $sql = "select * from lv_datos_tomo where completo = false 
            and digitados>0;";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findNoDigitados() {
        $sql = "select * from lv_datos_tomo where completo = false 
            and digitados=0;";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findDigitados() {
        $sql = "select * from lv_datos_tomo where completo = true;";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findNoDigitado($tomo){
        $sql = "select * from lv_tomo_asignacion where tomo=:tomo;";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('tomo', $tomo);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function findAsignados($usuario){
        $sql = "select * from lv_info_tomo where codi_tomo in(
            Select co_tomo from asignacion where co_asignado=:usuario);";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('usuario', $usuario);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findTomo($tomo){
        $sql = "select * from lv_asignacion WHERE tomo=$tomo";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        //print_r($stmt->fetchAll());exit;
        return $stmt->fetchAll();
    }

    public function findResumenFolios(array $filtro = null) {
        $where1 = array();
        $where2 = array();
        $where = array();
        $sql = "SELECT * FROM (select r.tomo,r.folios,r.resumen,r.digitables,r.digitados, 
            r.digitables-r.digitados as por_digitar, r.registros, (case when digitados = 0 then 'POR DIGITAR' 
            when r.digitados >0 and r.digitados <r.digitables then 'INCOMPLETO'
            when digitados = digitables then 'COMPLETO' end) as estado, digitados = digitables as completo
FROM (select t.codi_tomo as tomo, t.folios_tomo as folios,
(select count(f.codi_folio) from folios f where (f.reg_folio is null or f.reg_folio=0) and f.codi_tomo=t.codi_tomo) as resumen,
(select count(f.codi_folio) from folios f where (f.reg_folio is not null or f.reg_folio>0) and f.codi_tomo=t.codi_tomo) as digitables,
(select sum(f.reg_folio) from folios f where f.codi_tomo = t.codi_tomo) as registros,
count(distinct p.codi_folio) as digitados
from tomos t join folios ff
on ff.codi_tomo = t.codi_tomo
left join planilla_historicas p
on p.codi_folio = ff.codi_folio %s
GROUP BY t.codi_tomo) as r) as t %s;";
        if ($filtro['fecha-ini'] & $filtro['fecha-fin']) {
            $where1[] = "date(p.fec_creac) between :fecini and :fecfin";
            $where['fecini'] = $filtro['fecha-ini'];
            $where['fecfin'] = $filtro['fecha-fin'];
        }

        if (isset($filtro['tomo'])) {
            if (count($filtro['tomo']) && !in_array(null, $filtro['tomo'])) {
                $where2[] = 'tomo in (:tomo)';
                $where['tomo'] = $filtro['tomo'];
            }
        }
        if ($filtro['estado']) {
            $where2[] = "estado = :estado";
            $where['estado'] = $filtro['estado'];
        }
        $_where1 = count($where1) ? ' WHERE ' . implode(' AND ', $where1) : '';
        $_where2 = count($where2) ? ' WHERE ' . implode(' AND ', $where2) : '';
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tomo', 'tomo');
        $rsm->addScalarResult('folios', 'folios');
        $rsm->addScalarResult('resumen', 'resumen');
        $rsm->addScalarResult('digitables', 'digitables');
        $rsm->addScalarResult('digitados', 'digitados');
        $rsm->addScalarResult('por_digitar', 'por_digitar');
        $rsm->addScalarResult('registros', 'registros');
        $rsm->addScalarResult('estado', 'estado');
        $rsm->addScalarResult('completo', 'completo');
        $query = $this->getEntityManager()->createNativeQuery(
                sprintf($sql, $_where1, $_where2), $rsm);
        $query->setParameters($where);
        $tomos = $query->getArrayResult();
        return $tomos;
    }

    /**
     * 
     */
    public function getTotalTomos(){
        $sql = 'SELECT estado, count(tomo) as "tomos", sum(folios) as "folios", sum(resumen) as "foliosr", 
            sum(folios) - sum(resumen) as "foliosdd", sum(digitados) as "foliosd", 
            sum(por_digitar) as "foliosdg", sum(registros) as "registros"
    FROM (select * from lv_datos_tomo) as t
    GROUP BY estado;';
        $stm = $this->getEntityManager()->getConnection()->prepare($sql);
        $stm->execute();
        $data = $stm->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        return $data;
    }
}
