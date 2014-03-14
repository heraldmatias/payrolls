<?php

namespace Inei\Bundle\ConsistenciaBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
/**
 * Description of ConsistenciaService
 *
 * @author holivares
 */
class ConsistenciaService {

    private $em;
    private $mr;

    public function __construct(EntityManager $em, ManagerRegistry $mr) {
        $this->em = $em;
        $this->mr = $mr;
    }

    public function getPersonalDigitado() {
        try {
            $this->em->beginTransaction();
            $result = $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                    ->getPersonalDigitado();
            $this->em->commit();
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->em->rollback();
            $result = -1;
        }
        return $result;
    }

    public function findPersonal($criteria) {
        return $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                        ->findPersonal($criteria);
    }
    
    public function findPersonalEncontrado($criteria) {
        return $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                        ->findPersonalEncontrado($criteria);
    }

    public function findPersonalNoEncontrado($criteria) {
        return $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                        ->findPersonalNoEncontrado($criteria);
    }
    
    public function debeSincronizar() {
        $qb = $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                ->findPersonal();
        $qb->setMaxResults(1);
        $re = $qb->getOneOrNullResult();
        return null === $re;
    }

    public function processPersonalTodo() {
        $repository = $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado');
        try {
            $this->em->beginTransaction();
            $re1 = $repository->getByCodigoPersonal();
            $re2 = $repository->getByApellidosNombres();
            $re3 = $repository->getByNombresApellidos();
            $result = $re1 + $re2 + $re3;
            $this->em->commit();
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->em->rollback();
            $result = -1;
        }
        return $result;
    }
    
    public function findFamiliasNombres(){
        return $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                ->findFamiliasNombres();
    }
    
    public function findPersonalSiga($nombre){
        return $this->em->getRepository('IneiConsistenciaBundle:PersonalDigitado')
                ->findPersonalSiga2($nombre);
    }
    
    public function asociarPersonal($nombres, $persona, $source){
        //INTENTAR INSERTAR EN PERSONAL ENCONTRADO
//        print_r($nombres);
//        print_r($persona);
//        echo '<br><br>';
//        echo $source;
//        
//        exit;
        if($source===1){
            $obj = $this->em->getRepository('IneiConsistenciaBundle:PersonalEncontrado')
                    ->find($persona);
            $sqli = 'INSERT INTO personal_encontrado
                SELECT mp.codi_empl_per, mp.ape_pat_per, mp.ape_mat_per, 
                mp.nom_emp_per, mp.nomb_cort_per, mp.libr_elec_per 
                FROM maestro_personal mp WHERE mp.codi_empl_per=:persona';
            $_nombres = array();
            foreach ($nombres as $nombre) {
                $_nombres[] = preg_replace("('|´|,|\.| )", "", $nombre);
            }
            $nombres = "('".implode("','", $_nombres)."')";
            $sql = "UPDATE personal_digitado 
                SET codi_empl_per_persona = :persona
                WHERE regexp_replace(nomb_cort_per,'\W+', '','g') IN ".$nombres;
            try {
                $this->em->beginTransaction();
                if(null === $obj){
                    $this->em->getConnection()->executeUpdate($sqli,
                        array(
                            'persona' => $persona
                        ));
                }
                $this->em->getConnection()->executeUpdate($sql,
                        array(
                            'persona' => $persona
                        ));
                $result = true;
                $this->em->commit();
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->em->rollback();
                $result = false;
            }
        }else if($source===2){
            $cn = $this->mr->getManager('siga')->getConnection();
            
            $st = $cn->prepare("SELECT APEPAT, APEMAT, NOMBRE, DES_NOMBRE, DNI
                FROM PER_RENIEC WHERE DNI = :dni AND rownum=1");
            $st->bindValue(':dni', $persona);
            $st->execute();
            $obj = $st->fetch(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            unset($cn);
            unset($st);
            $sqli = 'SELECT fn_asocia_per_reniec(:apepat, :apemat, :nombre, :desnombre, :dni) AS codigo';
            $_nombres = array();
            foreach ($nombres as $nombre) {
                $_nombres[] = preg_replace("('|´|,|\.| )", "", $nombre);
            }
            $nombres = "('".implode("','", $_nombres)."')";
            $sql = "UPDATE personal_digitado 
                SET codi_empl_per_persona = :persona
                WHERE regexp_replace(nomb_cort_per,'\W+', '','g') IN ".$nombres;
            try {
                $this->em->beginTransaction();
                
                $stpr = $this->em->getConnection()->prepare($sqli);
                $stpr->bindValue(':apepat', $obj['APEPAT']);
                $stpr->bindValue(':apemat', $obj['APEMAT']);
                $stpr->bindValue(':nombre', $obj['NOMBRE']);
                $stpr->bindValue(':desnombre', $obj['DES_NOMBRE']);
                $stpr->bindValue(':dni', $obj['DNI']);                
                $stpr->execute();
                $data = $stpr->fetch(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                
                $persona = $data['codigo'];                
                /*actualizar*/
                $this->em->getConnection()->executeUpdate($sql,
                        array(
                            'persona' => $persona
                        ));
                $result = true;
                $this->em->commit();
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->em->rollback();                
                $result = false;
            }
        }
        return $result;
    }
    
    public function registraPersona($form){
        $sql1= 'INSERT INTO personal_encontrado(
            codi_empl_per, ape_pat_per, ape_mat_per, nom_emp_per, nomb_cort_per, 
            libr_elec_per)
            VALUES (:codigo, :ape_paterno, :ape_materno, :nombres, :nombre_completo, 
            :dni);
            ';
        $sql2= 'INSERT INTO maestro_personal(
            codi_empl_per, ape_pat_per, ape_mat_per, nom_emp_per, nomb_cort_per, 
            libr_elec_per)
            VALUES (:codigo, :ape_paterno, :ape_materno, :nombres, :nombre_completo, 
            :dni);
            ';
        $form['nombre_completo'] = sprintf('%s %s %s', $form['ape_paterno'],
                $form['ape_materno'], $form['nombres']);
        try {
            $this->em->beginTransaction();
            $this->em->getConnection()->executeUpdate($sql1,
                    $form);
            $this->em->getConnection()->executeUpdate($sql2,
                    $form);
            $result = true;
            $this->em->commit();
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->em->rollback();
            $result = false;
        }
        return $result;
    }
    
    public function printCSV($data){
        ob_start();
        $fp = fopen('php://output', 'w');
        foreach ($data as $item) {
            unset($item['id']);
            fputcsv($fp, $item);
        }
        fclose($fp);
        return ob_get_clean();
    }

    public function actualizaPeriodoSQL($new, $old){
        return sprintf("UPDATE folios SET per_folio='%s' WHERE per_folio='%s';\n",$new, $old);
//        try {
//            $this->em->beginTransaction();
//            $sql = "UPDATE folios SET per_folio=:new 
//            WHERE per_folio=:old";
//            $this->em->getConnection()->executeUpdate($sql, array('new' => $new,
//                'old' => $old)
//            );
//            $this->em->commit();
//        } catch (Doctrine\DBAL\DBALException $e) {
//            $this->em->rollback();
//        }
    }
    
    public function actualizaPeriodo($sql){        
        try {
            $this->em->beginTransaction();            
            $this->em->getConnection()->executeUpdate($sql);
            $this->em->commit();
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->em->rollback();
        }
    }
    
    public function getTomosFoliosRepetidos(){
        $st = $this->em->getConnection()->executeQuery('select codi_tomo, num_folio, count(num_folio) as repetidos from folios group by codi_tomo, num_folio having count(num_folio)>1;');
        $rows = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = array(
            'columns' => array('Tomo', 'Folio', 'Repeticiones','Opciones'),
            'rows' => $rows
        );
        return $data;
    }
    
    public function getTomosFoliosInconsistentes(){
        $st = $this->em->getConnection()->executeQuery('select fo.codi_tomo, fo.num_folio, fo.codi_folio, fo.existe 
from tomos t join 
(select f.codi_tomo, f.codi_folio, f.num_folio, EXISTS(select p.* from planilla_historicas p where p.codi_folio=f.codi_folio) as existe
from folios f
where (f.reg_folio = 0 OR f.reg_folio is null)) as fo
on t.codi_tomo = fo.codi_tomo
WHERE fo.existe=True;');
        $rows = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = array(
            'columns' => array('Tomo', 'Folio', 'Codigo', 'En planillas','Opciones'),
            'rows' => $rows
        );
        return $data;
    }
    
    public function getTomosFoliosRepetidosInfo($tomo, $folio){
        $st = $this->em->getConnection()->prepare('SELECT codi_folio, num_folio, codi_tomo, per_folio, 
            (select count(p.id) from planilla_historicas p where p.codi_folio=folios.codi_folio) as registros from folios where codi_tomo=:tomo and num_folio=:folio;');
        $st->bindValue(1, $tomo);
        $st->bindValue(2, $folio);
        $st->execute();
        $rows = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = array(
            'columns' => array('Codigo', 'Folio', 'Tomo', 'Periodo', 'Registros en Planilla','Cambiar', 'Opciones'),
            'rows' => $rows
        );
        return $data;
    }
    
    public function getTomosInconsistentesInfo($tomo){
        $st = $this->em->getConnection()->prepare('SELECT codi_tomo, folios_tomo, (select count(codi_folio) from folios f where f.codi_tomo=tomos.codi_tomo) as registrado,
            (select max(num_folio) from folios f where f.codi_tomo=tomos.codi_tomo) as ultimo from tomos where codi_tomo=:tomo; ');        
        $st->bindValue(1, $tomo);
        $st->execute();
        $rows = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = array(
            'columns' => array('Tomo', 'Folios Tomo', 'Folios Registrados', 'Ultimo Folio'),
            'rows' => $rows
        );
        return $data;
    }
    
    public function getTomosInconsistentes(){
        $st = $this->em->getConnection()->prepare('SELECT t.codi_tomo, t.folios_tomo, t.registrado, t.ultimo FROM 
            (SELECT codi_tomo, folios_tomo, (select count(codi_folio) from folios f where f.codi_tomo=tomos.codi_tomo) as registrado,
            (select max(num_folio) from folios f where f.codi_tomo=tomos.codi_tomo) as ultimo from tomos) as t 
            WHERE t.folios_tomo <> t.registrado OR t.folios_tomo <> t.ultimo; ');
        $st->execute();
        $rows = $st->fetchAll(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $data = array(
            'columns' => array('Tomo', 'Folios Tomo', 'Folios Registrados', 'Ultimo Folio', 'Opciones'),
            'rows' => $rows
        );
        return $data;
    }
    
    public function avanzarFolios($tomo, $folio){
        $st = $this->em->getConnection()->prepare('UPDATE folios SET num_folio=num_folio+1 WHERE codi_tomo=:tomo and num_folio>:folio;');
        $st->bindValue(1, $tomo);
        $st->bindValue(2, $folio);
        $st->execute();        
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function retrocederFolios($tomo, $folio){
        $st = $this->em->getConnection()->prepare('UPDATE folios SET num_folio=num_folio-1 WHERE codi_tomo=:tomo and num_folio>:folio;');
        $st->bindValue(1, $tomo);
        $st->bindValue(2, $folio);
        $st->execute();        
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function borrarFolio($codigo){
        $st = $this->em->getConnection()->prepare('DELETE FROM folios WHERE codi_folio = :codigo;');
        $st->bindValue(1, $codigo);        
        $st->execute();        
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function cambiarFolio($codigo, $nuevoFolio){
        $st = $this->em->getConnection()->prepare('Update folios SET num_folio=:nuevo WHERE codi_folio=:codigo;');
        $st->bindValue(1, $nuevoFolio);
        $st->bindValue(2, $codigo);
        $st->execute();        
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function aumentarFoliosTomo($tomo, $folios){
        $st = $this->em->getConnection()->prepare('UPDATE tomos SET folios_tomo=:folios WHERE codi_tomo=:tomo;');
        $st->bindValue(1, $folios);
        $st->bindValue(2, $tomo);
        $st->execute();        
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function disminuirFoliosTomo($tomo, $folio){
        $st = $this->em->getConnection()->prepare('DELETE FROM folios WHERE codi_tomo=:tomo and num_folio>:folio;');
        $st->bindValue(1, $tomo);
        $st->bindValue(2, $folio);
        $st->execute();
        $st1 = $this->em->getConnection()->prepare('UPDATE tomos SET folios_tomo = :folio WHERE codi_tomo=:tomo;');
        $st1->bindValue(1, $folio);
        $st1->bindValue(2, $tomo);
        $st1->execute();
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
    
    public function asignarPersonalNoEncontrado($nombres){
        $_nombres = array();
        foreach ($nombres as $nombre) {
            $_nombres[] = preg_replace("('|´|,|\.| )", "", $nombre);
        }
        $nombres = "('".implode("','", $_nombres)."')";
        $st = $this->em->getConnection()->prepare("Update personal_digitado SET fl_existe = false WHERE regexp_replace(nomb_cort_per,'\W+', '','g') IN ".$nombres);
        $st->execute();
        $data = array(
            'success' => true,
            'data' => true,
            'error'=> Null
        );
        return $data;
    }
}