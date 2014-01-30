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
    
    public function validateTomo(){
        $sql = 'SELECT f.codi_tomo as tomo, f.num_folio as folio, 
            fn_conceptos_repetidos(f.codi_folio) as concepto
            FROM conceptos_folios cf JOIN folios f
            ON cf.codi_folio = f.codi_folio
            --WHERE f.codi_tomo = 89
            GROUP BY cf.codi_conc_tco, f.num_folio, f.codi_tomo, f.codi_folio
            HAVING COUNT(cf.codi_conc_tco)>=2
            ORDER BY f.codi_tomo, f.num_folio;
            ';
        $stmt = $this->em->getConnection()->executeQuery($sql);
        $data = $stmt->fetchAll();
        $_data = array();
        $tomos = array_unique(array_map(function($item) { return $item['tomo']; }, $data));
        //$data = array_unique(array_map(function($item) { unset($item['tomo']); return $item; }, $data));
        foreach ($tomos as &$tomo) {
            $folios =  array_map(function($item) { unset($item['tomo']); return array_values($item); },
                    array_filter($data, function($item) use ($tomo) { return $item['tomo']=== $tomo; }));
            //$dtomo['tomo'] = $tomo;
            //$dtomo['folios'] = $folios;
            $_data[] = array('TOMO - '.$tomo);
            $_data[] = array('FOLIOS', 'CONCEPTOS');
            $_data = array_merge($_data, $folios);
        }
//        print_r($_data);
//        exit;
        return $_data;
    }
        
    public function getReporte($ntomo, $title, $from){
        $tomo = $this->em->getRepository('IneiPayrollBundle:Tomos')->find($ntomo);
        $rfolio = $this->em->getRepository('IneiPayrollBundle:Folios');
        $data = array();
        for ($index = 1; $index <= $tomo->getFoliosTomo(); $index++) {
            $row = $rfolio->findFolioInventarioByNum($index, $ntomo);
            if($row !== NULL){
                $data[] = $row;
            }
        }
        return $this->printReporte($data, $title, $tomo, $from);
    }
    
    public function printReporte(array $rows, $title, $tomo, $from = 4) {
        $objPHPExcel = new \PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator("INEI")
                ->setLastModifiedBy("INEI")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Reporte")
                ->setCategory("Reporte");
// Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValueByColumnAndRow(0, 1, $title);
        /*FOLIOS */
        foreach (array('Año', 'Periodo', 'Tomo', 'Folios') as $key => $value) {
            $sheet->setCellValueByColumnAndRow($key>1?$key*2:$key+1, 3, $value)
                    ->getStyle()->getFont()->setBold(true);
            switch ($key){
                case 0:
                    $sheet->setCellValueByColumnAndRow($key>1?$key*2:$key+1, 4, $tomo->getAnoTomo())
                    ->getStyle()->getFont()->setBold(true);
                    break;
                case 1:
                    $sheet->setCellValueByColumnAndRow($key>1?$key*2:$key+1, 4, $tomo->getPeriodoTomo())
                    ->getStyle()->getFont()->setBold(true);
                    break;
                case 2:
                    $sheet->setCellValueByColumnAndRow($key>1?$key*2:$key+1, 4, $tomo->getCodiTomo())
                    ->getStyle()->getFont()->setBold(true);
                    break;
                case 3:
                    $sheet->setCellValueByColumnAndRow($key>1?$key*2:$key+1, 4, $tomo->getFoliosTomo())
                    ->getStyle()->getFont()->setBold(true);
                    break;
            }
        }
        /**cabecera del folio*/
        foreach (array('Folio', 'Periodo', 'Registro', 'Tipo') as $key => $value) {
            $sheet->setCellValueByColumnAndRow($key, 6, $value)
                    ->getStyle()->getFont()->setBold(true);
        }
        for ($index = 1; $index <= 40; $index++) {
            $sheet->setCellValueByColumnAndRow($index+3, 6, 'Campo '. $index)
                    ->getStyle()->getFont()->setBold(true);
        }
        foreach ($rows as $row => $rcell) {
            foreach ($rcell as $col => $cell) {
                $sheet->setCellValueByColumnAndRow($col, $row + $from, $cell);
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }
}