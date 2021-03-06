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
    public function getByUsername(array $filtro=null){
        $_where = array();
        $fini = $filtro['fecha-ini'];
        $ffin = $filtro['fecha-fin'];
        $user = $filtro['digitador'];
        $turno = isset($filtro['turno'])? $filtro['turno']:FALSE;
        //\DateTime()->format('Y-m-d')
        if($fini & $ffin){
            if($turno){
                switch ($turno) {
                    case '1':
                        $fini = $fini . ' 08:00:00';
                        $ffin = $ffin . ' 15:10:00';
                        break;
                    case '2':
                        $fini = $fini . ' 15:10:01';
                        $ffin = $ffin . ' 21:30:00';
                        break;
                }
                $_where[] = "fec_creac between '$fini' and '$ffin'";
            }else{
                $_where[] = "date(fec_creac) between '$fini' and '$ffin'";
            }
        }        
        if($user){
            $_where[] = "usu_crea_id = $user";
        }
        $where = (count($_where))?' WHERE '.implode(' and ', $_where):'';
        $sql = "select tabla.digitador, tabla.tomo, tabla.folios, tabla.resumen,
            tabla.folios-tabla.resumen as digitables, tabla.folios_digitados,            
            round((folios_digitados/(folios-resumen))*100,2) as porcentaje_folios,
            tabla.registros, tabla.digitados,
            lva.tregistros, lva.tdias,
            round((digitados/registros)*100,2) as porcentaje_registros
            from (Select pla.digitador, f.codi_tomo as tomo, count(pla.codi_folio)::numeric as folios_digitados, 
(select count(ff.codi_folio) from
folios ff where ff.codi_tomo = f.codi_tomo) as folios,
(select count(ff.codi_folio) from folios ff 
where (ff.reg_folio is null or ff.reg_folio=0) and ff.codi_tomo=f.codi_tomo) as resumen,
sum(pla.cantidad) as digitados,
(select sum(ff.reg_folio) from
folios ff where ff.codi_tomo = f.codi_tomo) as Registros
FROM 
(select p.codi_folio,u.cod_usu as digitador, count(distinct p.num_reg) as cantidad
from planilla_historicas p
join usuarios u 
on u.id = p.usu_crea_id $where
group by p.codi_folio,u.cod_usu) as pla
 join folios f
on pla.codi_folio = f.codi_folio 
group by f.codi_tomo, pla.digitador
order by f.codi_tomo) as tabla 
join lv_acumulados_tomo lva
on lva.tomo = tabla.tomo
order by tabla.digitador, tabla.registros DESC;";
        //echo $sql;exit;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}
