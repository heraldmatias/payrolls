<?php

namespace Inei\Bundle\PayrollBundle\Service;

/**
 * Description of ExcelService
 *
 * @author holivares
 */
class ExcelService {

    public function printReporte(array $rows, array $cols, $title, $from = 4) {
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
        foreach ($cols as $col => $ccell) {
            $sheet->setCellValueByColumnAndRow($col, 3, $ccell)
                    ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }
        foreach ($rows as $row => $rcell) {
            $count = count($rcell);
            foreach ($cols as $col => $ccell) {
                $sheet->setCellValueByColumnAndRow($col, $row + $from, ($col < $count) ? $rcell[$col] : null);
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

}