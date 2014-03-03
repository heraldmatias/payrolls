<?php

namespace Inei\Bundle\PayrollBundle\Service;

use Doctrine\ORM\EntityManager;

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

    public function orderFolios($folio, $folioupdate, $tomo) {
        /*$conn = $this->em->getConnection();
        if (null === $folioupdate | trim($folioupdate) === '') {
            $sql = "SELECT codi_folio FROM folios 
                WHERE codi_tomo=$tomo and num_folio=$folio";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $f = $stmt->fetch();
            if ($f) {
                $sql = "UPDATE folios 
                SET num_folio=num_folio+1 WHERE codi_tomo=$tomo and num_folio>=$folio ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
        } else if ($folio != $folioupdate) {
            $sql = "SELECT codi_folio FROM folios 
                WHERE codi_tomo=$tomo and num_folio=$folio";
            $stmt1 = $conn->prepare($sql);
            $stmt1->execute();
            $result = $stmt1->fetch();
            $f = $result['codi_folio'];
            $sql3 = "UPDATE folios 
                SET num_folio=$folioupdate WHERE codi_folio = $f";
            $conn->prepare($sql3)->execute();
        }*/
        return true;
    }

    public function deleteFolio($folio) {
        $conn = $this->em->getConnection();
        try {
            $conn->beginTransaction();            
            $sql = "UPDATE folios SET num_folio=num_folio-1 WHERE 
                codi_tomo=(SELECT codi_tomo FROM folios WHERE codi_folio=:folio) 
                and num_folio>(SELECT num_folio FROM folios WHERE codi_folio=:folio) ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('folio', $folio);
            $stmt->execute();
            $this->em->createQuery('delete from IneiPayrollBundle:ConceptosFolios 
                m where m.codiFolio = ' . $folio)->execute();
            $this->em->createQuery('delete from IneiPayrollBundle:Folios 
                m where m.codiFolio = ' . $folio)->execute();
            $this->em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas 
                m where m.folio = ' . $folio)->execute();
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    public function deleteConcepto($concepto){
        $conn = $this->em->getConnection();
        $sql = "SELECT fn_position_concepto_update(:concepto, :folio, :id) as c;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('concepto', $concepto->getCodiConcTco()->getCodiConcTco());
        $stmt->bindValue('folio', $concepto->getCodiFolio()->getCodiFolio());
        $stmt->bindValue('id', $concepto->getId());
        $stmt->execute();
        $pos = $stmt->fetch();
        $pos = ($pos['c']===null)?0:$pos['c'];
        //exit;
        $conn->executeQuery('DELETE FROM planilla_historicas WHERE 
            codi_folio = :folio AND codi_conc_tco=:concepto
            AND flag_folio= :pos', array(
                'folio' => $concepto->getCodiFolio()->getCodiFolio(),
                'concepto' => $concepto->getCodiConcTco()->getCodiConcTco(),
                'pos' => $pos
            ));
        $conn->executeQuery('UPDATE planilla_historicas SET flag_folio = flag_folio-1
            WHERE codi_folio = :folio AND codi_conc_tco=:concepto
            AND flag_folio>:pos', array(
                'folio' => $concepto->getCodiFolio()->getCodiFolio(),
                'concepto' => $concepto->getCodiConcTco()->getCodiConcTco(),
                'pos' => $pos
            ));
        $this->em->remove($concepto);
        return true;
    }
    
    public function updateMatrix($folio) {
        $conn = $this->em->getConnection();
        try {
            $conn->beginTransaction();            
            $sql = "SELECT fn_fixplanilla(:acodi_folio);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('acodi_folio', $folio);
            $stmt->execute();
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    public function updatePeriodoFolio($data) {
        $conn = $this->em->getConnection();
        try {
            $conn->beginTransaction();            
            $sql = "UPDATE folios SET tipo_folio=:tipo, desc_folio=:descripcion, 
                mes_folio=:mes, rango_folio=:rango, fec_inicio=:finicio, per_folio = :periodo,
                fec_final=:ffinal, ano_folio=:ano WHERE codi_tomo=:tomo AND num_folio=:folio";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('tipo', $data['tipoFolio']);
            $stmt->bindValue('descripcion', $data['descFolio']);
            $stmt->bindValue('periodo', $data['periodoFolio']);
            $stmt->bindValue('ano', $data['anoFolio']);
            $stmt->bindValue('tomo', $data['tomo']);
            $stmt->bindValue('folio', $data['folio']);
            if(array_key_exists('mesFolio', $data)){
                $stmt->bindValue('mes', $data['mesFolio']);
            }else{
                $stmt->bindValue('mes', NULL);
            }            
            if(array_key_exists('rango', $data)){
                $stmt->bindValue('rango', $data['rango']);
            }else{
                $stmt->bindValue('rango', NULL);
            }
            if(array_key_exists('fecInicio', $data)){
                $stmt->bindValue('finicio', $data['fecInicio']);
            }else{
                $stmt->bindValue('finicio', NULL);
            }
            if(array_key_exists('fecFinal', $data)){
                $stmt->bindValue('ffinal', $data['fecFinal']);
            }else{
                $stmt->bindValue('ffinal', NULL);
            }
            $stmt->execute();
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    public function siguienteFolioDigitable($tomo, $folio){
        $_folio = $this->em->getRepository('IneiPayrollBundle:Folios')
                ->findSiguienteFolioDigitable($tomo, $folio);
        $nextFolio = null;
        if($_folio)
            $nextFolio = $_folio['folio'];
        return $nextFolio;
    }
}
