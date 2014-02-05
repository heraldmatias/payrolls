<?php

namespace Inei\Bundle\ConsistenciaBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PersonalDigitadoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonalDigitadoRepository extends EntityRepository {

    /**
     * Obtiene todos los nombres de empleados con su respectivo codigo
     * si lo tuviera, y los inserta en la tabla personal digitado
     * @return integer 0 en exito
     */
    public function getPersonalDigitado() {
        $sql_del = 'DELETE FROM personal_digitado;';
        $sql_del2 = 'DELETE FROM personal_encontrado;';
        $sql = 'INSERT INTO personal_digitado(id, nomb_cort_per, codi_empl_per, nomb_soundex_per) 
SELECT row_number() OVER(ORDER BY empleado ASC), empleado, codigo, soundex 
FROM lv_personal_planillas
ORDER BY empleado;';
        $con = $this->getEntityManager()->getConnection();

        $stmt1 = $con->prepare($sql_del);
        $stmt1->execute();

        $con->executeUpdate($sql_del2);
        
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $this->getCountPersonal();
    }

    public function findPersonalNoEncontrado(array $criteria) {
        $DQL = "SELECT pd FROM IneiConsistenciaBundle:PersonalDigitado 
            pd LEFT JOIN pd.persona pe ";
        $where = array('pd.persona IS NULL');
        if (array_key_exists('soundex', $criteria))
            $where[] = "pd.soundex = soundex('" . $criteria['soundex']."')";
        if (array_key_exists('nombres', $criteria))
            $where[] = "pd.nombCortPer LIKE '%" . $criteria['nombres']."%'";
        if (array_key_exists('codigo', $criteria))
            $where[] = "pd.codiEmplPer ='" . $criteria['codigo'] . "'";
        
        $DQL .= count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';
        $DQL .= ' ORDER BY pd.soundex, pd.nombCortPer';        
        $qb = $this->getEntityManager()->createQuery($DQL);        
        return $qb;
    }

    public function findPersonalEncontrado($criteria = array()) {
        $DQL = "SELECT pd FROM IneiConsistenciaBundle:PersonalEncontrado pd ";
        $where = array();
        if (array_key_exists('soundex', $criteria))
            $where[] = "soundex(pd.nombCortPer) = soundex('" . $criteria['soundex']."')";
        if (array_key_exists('nombres', $criteria))
            $where[] = "pd.nombCortPer LIKE '%" . $criteria['nombres']."%'";
        if (array_key_exists('codigo', $criteria))
            $where[] = "pe.codiEmplPer ='" . $criteria['codigo'] . "'";
        
        $DQL .= count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';
        $DQL .= ' ORDER BY pd.nombCortPer';
        $qb = $this->getEntityManager()->createQuery($DQL);
        return $qb;
    }

    public function findPersonal($criteria = array()) {
        $DQL = "SELECT pd FROM IneiConsistenciaBundle:PersonalDigitado pd ";
        $where = array();
        if (array_key_exists('soundex', $criteria))
            $where[] = "pd.soundex = soundex('" . $criteria['soundex']."')";
        if (array_key_exists('nombres', $criteria))
            $where[] = "pd.nombCortPer LIKE '%" . $criteria['nombres']."%'";
        if (array_key_exists('codigo', $criteria))
            $where[] = "pd.codiEmplPer ='" . $criteria['codigo'] . "'";
        
        $DQL .= count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';
        $DQL .= ' ORDER BY pd.soundex';
        $qb = $this->getEntityManager()->createQuery($DQL);
        return $qb;
    }

    /**
     * Busca por codigo en el maestro personal del SIGA, si obtiene resultados
     * va anexando a la tabla de personal_encontrado y posteriormente enlaza
     * los nombres asociados a dicha persona con su codigo de personal
     * @return integer
     */
    public function getByCodigoPersonal() {
        $sql = 'SELECT mp.codi_empl_per, mp.ape_pat_per, mp.ape_mat_per, 
            mp.nom_emp_per, mp.nomb_cort_per, mp.libr_elec_per 
            FROM maestro_personal mp JOIN personal_digitado lvp 
ON mp.codi_empl_per = lvp.codi_empl_per 
LEFT JOIN personal_encontrado pe ON pe.codi_empl_per = mp.codi_empl_per
WHERE pe.codi_empl_per IS NULL AND lvp.codi_empl_per_persona IS NULL
GROUP BY mp.codi_empl_per';
        $sql_ins = 'INSERT INTO personal_encontrado '.$sql;
        $result = $this->getCountQuery($sql);
        $sql_upd = 'UPDATE personal_digitado SET codi_empl_per_persona = t.codi_empl_per 
FROM (
SELECT mp.codi_empl_per FROM maestro_personal mp JOIN personal_digitado lvp 
ON mp.codi_empl_per = lvp.codi_empl_per
GROUP BY mp.codi_empl_per
) t
WHERE personal_digitado.codi_empl_per = t.codi_empl_per;';
        $con = $this->getEntityManager()->getConnection();
        
        $stmt1 = $con->prepare($sql_ins);
        $stmt1->execute();
        
        $stmt = $con->prepare($sql_upd);
        $stmt->execute();
        
        return $result;
    }

    /**
     * Busca por apellidos y nombres en el maestro personal del SIGA, si obtiene
     * resultados va anexando a la tabla de personal encontrado y posteriormente
     * enlaza los nombres asociados a dicha persona con su codigo de personal
     * @return integer
     */
    public function getByApellidosNombres() {
        $sql = 'SELECT mp.codi_empl_per, mp.ape_pat_per, mp.ape_mat_per, 
            mp.nom_emp_per, mp.nomb_cort_per, mp.libr_elec_per 
            FROM maestro_personal mp JOIN personal_digitado lvp 
ON mp.nomb_cort_per = lvp.nomb_cort_per 
LEFT JOIN personal_encontrado pe ON pe.codi_empl_per = mp.codi_empl_per
WHERE lvp.codi_empl_per_persona IS NULL AND 
pe.codi_empl_per IS NULL
GROUP BY mp.codi_empl_per, mp.nomb_cort_per';
        $sql_ins = 'INSERT INTO personal_encontrado '.$sql;
        $result = $this->getCountQuery($sql);
        $sql_upd = 'UPDATE personal_digitado SET codi_empl_per_persona = t.codi_empl_per 
FROM (
SELECT mp.codi_empl_per, mp.nomb_cort_per FROM maestro_personal mp JOIN personal_digitado lvp 
ON mp.nomb_cort_per = lvp.nomb_cort_per 
WHERE lvp.codi_empl_per_persona IS NULL
GROUP BY mp.codi_empl_per, mp.nomb_cort_per
) t
WHERE personal_digitado.nomb_cort_per = t.nomb_cort_per;';
        $con = $this->getEntityManager()->getConnection();
        
        $stmt1 = $con->prepare($sql_ins);
        $stmt1->execute();
        $stmt1->fetch();
        $stmt = $con->prepare($sql_upd);
        $stmt->execute();
        
        return $result;
    }

    /**
     * Busca por nombres y apellidos en el maestro personal del SIGA, si obtiene
     * resultados va anexando a la tabla de personal encontrado y posteriormente
     * enlaza los nombres asociados a dicha persona con su codigo de personal
     * @return integer
     */
    public function getByNombresApellidos() {
        $sql = "SELECT mp.codi_empl_per, mp.ape_pat_per, mp.ape_mat_per, 
            mp.nom_emp_per, mp.nomb_cort_per, mp.libr_elec_per 
            FROM maestro_personal mp JOIN personal_digitado lvp 
ON concat(mp.nom_emp_per, ' ', mp.ape_pat_per, ' ',mp.ape_mat_per) = lvp.nomb_cort_per 
LEFT JOIN personal_encontrado pe ON pe.codi_empl_per = mp.codi_empl_per
WHERE lvp.codi_empl_per_persona IS NULL AND 
pe.codi_empl_per IS NULL
GROUP BY mp.codi_empl_per, mp.nomb_cort_per";
        $sql_ins = 'INSERT INTO personal_encontrado '.$sql;
        $result = $this->getCountQuery($sql);
        $sql_upd = "UPDATE personal_digitado SET codi_empl_per_persona = t.codi_empl_per 
FROM (
SELECT mp.codi_empl_per, mp.nom_emp_per,mp.ape_pat_per, mp.ape_mat_per, mp.libr_elec_per FROM maestro_personal mp JOIN personal_digitado lvp 
ON concat(mp.nom_emp_per, ' ', mp.ape_pat_per, ' ',mp.ape_mat_per) = lvp.nomb_cort_per 
LEFT JOIN personal_encontrado pe ON pe.codi_empl_per = mp.codi_empl_per
WHERE lvp.codi_empl_per_persona IS NULL 
GROUP BY mp.codi_empl_per, mp.nomb_cort_per
) t
WHERE personal_digitado.nomb_cort_per = 
concat(t.nom_emp_per, ' ', t.ape_pat_per, ' ', t.ape_mat_per);";
        $con = $this->getEntityManager()->getConnection();
        
        $stmt1 = $con->prepare($sql_ins);
        $stmt1->execute();
        $stmt = $con->prepare($sql_upd);
        $stmt->execute();
        return $result;
    }

    private function getCountQuery($sql) {
        $sqlc = 'SELECT count(*) as cantidad FROM (' . $sql . ') as T';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sqlc);
        $stmt1->execute();
        $fila = $stmt1->fetch();
        return $fila['cantidad'];
    }

    public function getCountPersonal(){
        $sqlc = 'SELECT count(nomb_cort_per) as cantidad FROM personal_digitado;';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sqlc);
        $stmt1->execute();
        $fila = $stmt1->fetch();
        return $fila['cantidad'];
    }
    
    public function getCountPersonalEncontrado(){
        $sqlc = 'SELECT count(nomb_cort_per) as cantidad FROM personal_encontrado;';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sqlc);
        $stmt1->execute();
        $fila = $stmt1->fetch();
        return $fila['cantidad'];
    }
    
    public function findFamiliasNombres(){
        $sql = 'SELECT nomb_soundex_per FROM personal_digitado 
            GROUP BY nomb_soundex_per order by nomb_soundex_per;';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sql);
        $stmt1->execute();
        $result = $stmt1->fetchAll();
        return $result;
    }
    
    public function findPersonalSiga($nombres){
        $filtro = array();
        foreach ($nombres as $nombre){            
            $_nombre = explode(' ', $nombre);
            $palabras = array();
            foreach ($_nombre as $palabra){
                if(!in_array(strtolower($palabra), array('de', 'del','los','las')) 
                        & strlen($palabra)>2){
                    $f = "nomb_cort_per LIKE '%$palabra%'";
                    if(!in_array($f, $filtro)){
                        $palabras[] = $f;
                    }
                }
            }
            $filtro[] = implode(' AND ', $palabras);
        }
//        print_r($filtro);exit;
        $nombres = implode(' OR ', $filtro);
        $sql = "SELECT codi_empl_per as codigo, 
            concat(codi_empl_per, ' - ',nomb_cort_per) as nombres 
            FROM maestro_personal Where ".$nombres.' ORDER BY nomb_cort_per';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sql);
        $stmt1->execute();
        $result = $stmt1->fetchAll();
        return $result;
    }
    
    public function findPersonalSiga2($nombre){
        $filtro = "nomb_cort_per LIKE '$nombre%'";
        
//        print_r($filtro);exit;
        
        $sql = "SELECT codi_empl_per as \"VALUE\", 
            concat(codi_empl_per, ' - ',nomb_cort_per) as \"LABEL\"
            FROM maestro_personal Where ".$filtro.' ORDER BY nomb_cort_per LIMIT 5';
        $con = $this->getEntityManager()->getConnection();
        $stmt1 = $con->prepare($sql);
        $stmt1->execute();
        $result = $stmt1->fetchAll();
        return $result;
    }
}
