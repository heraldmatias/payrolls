<?php

namespace Inei\Bundle\ConsistenciaBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Description of ConsistenciaService
 *
 * @author holivares
 */
class ConsistenciaService {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
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
    
    public function asociarPersonal($nombres, $persona){
        //INTENTAR INSERTAR EN PERSONAL ENCONTRADO
        $obj = $this->em->getRepository('IneiConsistenciaBundle:PersonalEncontrado')
                ->find($persona);
        $sqli = 'INSERT INTO personal_encontrado
            SELECT mp.codi_empl_per, mp.ape_pat_per, mp.ape_mat_per, 
            mp.nom_emp_per, mp.nomb_cort_per, mp.libr_elec_per 
            FROM maestro_personal mp WHERE mp.codi_empl_per=:persona';
        $nombres = "('".implode("','", $nombres)."')";
        $sql = 'UPDATE personal_digitado 
            SET codi_empl_per_persona = :persona
            WHERE nomb_cort_per IN '.$nombres;
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

    public function actualizaPeriodo($new, $old){
        try {
            $this->em->beginTransaction();
            $sql = "UPDATE folios SET per_folio=:new 
            WHERE per_folio=:old";
            $this->em->getConnection()->executeUpdate($sql, array('new' => $new,
                'old' => $old)
            );
            $this->em->commit();
        } catch (Doctrine\DBAL\DBALException $e) {
            $this->em->rollback();
        }
    }
}