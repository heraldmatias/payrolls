<?php

namespace Inei\Bundle\PayrollBundle\Service;

use Doctrine\ORM\EntityManager;
use Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of PlanillaService
 *
 * @author holivares
 */
class PlanillaService {

    private $em;
    private $sc;

    public function __construct(EntityManager $em, SecurityContextInterface $sc) {
        $this->em = $em;
        $this->sc = $sc;
    }

    /**
     * Genera el nombre del archivo con la siguiente nomenclatura
     * codigo de usuario_numero de tomo_folio 
     * @param integer $tomo
     * @param integer $folio
     * @return string
     */
    private function getAutoSaveFilename($tomo, $folio) {
        $userid = $this->sc->getToken()->getUser()->getId();
        $filename = 'planilla_' . $userid . '_' . $tomo . '_' . $folio . '.json';
        $fp = __DIR__ . '/../../../../../web/' . $filename;
        return $fp;
    }

    /**
     * Genera un nombre de archivo con el numero de tomo y folio donde 
     * guardara el array planilla en formato json
     * @param integer $tomo
     * @param integer $folio
     * @param array $planilla
     * @return string
     */
    public function autoSave($tomo, $folio, $planilla) {

        $date = new \DateTime();
        $data = json_encode($planilla);
        $fp = $this->getAutoSaveFilename($tomo, $folio);
        file_put_contents($fp, $data);

        $response = 'El documento se guardo por ultima vez ' . $date->format('Y-m-d H:i:s');

        return $response;
    }

    /**
     * Buscara si existe un archivo json con el numero de folio y tomo 
     * proporcionados
     * @param integer $tomo
     * @param integer $folio
     * @return type
     */
    public function loadAutoSave($tomo, $folio) {
        $fp = $this->getAutoSaveFilename($tomo, $folio);
        $result = null;
        if (file_exists($fp)) {
            $data = file_get_contents($fp);
            $result = json_decode($data, true);
        }
        return $result;
    }

    public function getCountPlanillas($codiFolio) {
        $DQL = "
            SELECT p.registro, p.id 
            FROM IneiPayrollBundle:PlanillaHistoricas p
            WHERE p.folio = :folio
            ORDER BY p.registro ASC
        ";
        $qb = $this->em
                ->createQuery($DQL)
                ->setParameter('folio', $codiFolio);
        $result = $qb->getArrayResult();
        $group = array();
        if (!$result)
            return array();
        $id = $result[0]['registro'];
        $ids = array();
        $co = 0;
        foreach ($result as $planilla) {
            if ($id == $planilla['registro']) {
                $id = $planilla['registro'];
                $ids[] = $planilla['id'];
                continue;
            }
            $group[$co] = $ids;
            $ids = array();
            $ids[] = $planilla['id'];
            $id = $planilla['registro'];
            $co++;
        }
        $group[$co] = $ids;
        return $group;
    }

    /**
     * Obtiene los registros de planilla para un determinado folio
     * @param integer $codiFolio
     * Codigo de folio
     * @return type
     */
    public function getPlanillas($codiFolio) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
                ->from('IneiPayrollBundle:PlanillaHistoricas', 'c')
                ->where('c.folio = :folio')
                ->orderBy('c.registro', 'ASC')
                ->setParameter('folio', $codiFolio);
        $planillas = $qb->getQuery()->getResult();
        return $planillas;
    }

    /**
     * Genera un array de inputs de acuerdo al numero de registros y
     * conceptos del folio
     * @param Folios $object  Instancia del folio
     * @param boolean $autosave Si es true buscara si hay algun autoguardado
     * @return array
     */
    public function generateMatrix($object, $autosave = false) {
        $_planillas = null;
        if ($autosave) {
            $_planillas = $this->
                    loadAutoSave($object->getTomo()->getCodiTomo(), $object->getFolio());
        }
        if (null !== $_planillas) {
            return $_planillas;
        } else {
            $_planillas = $this->getPlanillas($object->getCodiFolio());
        }
        $planilla = array();
        $array = array('payrolls' => array_map(
                    create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
        $co = 0;
        if ($_planillas) {
            $reg = $_planillas[0]->getRegistro();
            foreach ($_planillas as $key => $value) {
                if ($reg == $value->getRegistro()) {
                    $reg = $value->getRegistro();
                    $planilla['registro'] = $reg;
                    $planilla['codiEmplPer'] = $value->getCodiEmplPer();
                    $planilla['descripcion'] = $value->getDescripcion();
                    $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                    $planilla[$key] = $value->getValoCalcPhi();
                    continue;
                }
                $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                $array['payrolls'][$co] = $planilla;
                $reg = $value->getRegistro();
                $planilla['codiEmplPer'] = strtoupper($value->getCodiEmplPer());
                $planilla['descripcion'] = $value->getDescripcion();
                $planilla[$key] = $value->getValoCalcPhi();
                $co++;
                if ($co > $object->getRegistrosFolio() - 1)
                    break;
            }
            if ($co <= $object->getRegistrosFolio() - 1)
                $array['payrolls'][$co] = $planilla;
//        } else {
//            $array = array('payrolls' => array_map(
//                        create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
        }
        return $array;
    }
    
    public function getPlanillaColumns($object) {
        if(!$object) return array();
        $array = $this->em->getRepository('IneiPayrollBundle:ConceptosFolios')
                ->getForColumns($object->getCodiFolio());
        $result = array_map(
                create_function('$item', 'return $item["descCortTco"];'), $array);
        return array_merge(array('REGISTRO','NOMBRES Y APELLIDOS','OBSERVACION'), $result);
    }
    
    public function getPlanillaValues($object) {
        if(!$object) return array();
        $_planillas = $this->getPlanillas($object->getCodiFolio());
        $planilla = array();
        $array = array();$co=0;
        if ($_planillas) {
            $reg = $_planillas[0]->getRegistro();
            foreach ($_planillas as $key => $value) {
                if ($reg == $value->getRegistro()) {
                    $reg = $value->getRegistro();
                    $planilla['registro'] = ($reg+1);
                    $planilla['codiEmplPer'] = $value->getCodiEmplPer();
                    $planilla['descripcion'] = $value->getDescripcion();
                    $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                    $planilla[$key] = $value->getValoCalcPhi();
                    continue;
                }
                $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                $array[$co] = $planilla;
                $reg = $value->getRegistro();
                $planilla['codiEmplPer'] = strtoupper($value->getCodiEmplPer());
                $planilla['descripcion'] = $value->getDescripcion();
                $planilla[$key] = $value->getValoCalcPhi();
                $co++;
            }
            $array[$co] = $planilla;
        }
        $result = array_map(
                create_function('$item', 'return array_values($item);'), $array);
        return $result;
    }

    /**
     * Guarda la matriz de planilla para el folio dado
     * @param Folios $object Una instancia de Folio
     * @param array $data Array con la data a guardar cuyo index principal
     * debe llamarse payroll
     * @return boolean
     */
    public function saveMatrix($object, $data) {
        try {
            $q = $this->em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas m where m.folio = ' . $object->getCodiFolio());
            $q->execute();
            $userid = $this->sc->getToken()->getUser()->getId();
            $fecha = new \DateTime();
            foreach ($data as $key1 => $planilla) {
                $reg = $planilla['registro'];
                $dni = $planilla['codiEmplPer'];
                $descripcion = $planilla['descripcion'];
                //unset($results[$key1]);
                unset($planilla['codiEmplPer']);
                unset($planilla['descripcion']);
                unset($planilla['registro']);
                //FALTA JALAR LA FILA key para coger correctamente los ids

                foreach ($planilla as $key => $valor) {
                    $planillah = new PlanillaHistoricas();
                    $pos = strpos($key, '_');
                    $planillah->setCodiConcTco(substr($key, 0, $pos));
                    $planillah->setFlag(substr($key, $pos + 1));
                    $planillah->setTipoPlanTpl($object->getTipoPlanTpl()->getTipoPlanTpl());
                    $planillah->setSubtPlanTpl($object->getSubtPlanStp());
                    $planillah->setNumePeriTpe(01);
                    $planillah->setDescripcion($descripcion);
                    $planillah->setValoCalcPhi((null === $valor) ? '0' : $valor);
                    $planillah->setCodiEmplPer($dni);
                    $planillah->setAnoPeriTpe($object->getTomo()->getAnoTomo());
                    $planillah->setFolio($object->getCodiFolio());
                    $planillah->setRegistro($reg);
                    $planillah->setCreador($userid);
                    $planillah->setFecCreac($fecha);
                    $this->em->persist($planillah);
                }
            }/*             * ELIMINA REGISTROS SOBRANTES PORQUE LOS DATOS DEL FOLIO FUERON MODIFICADOS* */

            $this->em->flush();
            $this->em->clear();
            $filename = $this->getAutoSaveFilename(
                    $object->getTomo()->getCodiTomo(), $object->getFolio());
            if (file_exists($filename)) {
                unlink($filename);
            }
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            return false;
        } catch(\Exception $ee){
            return false;
        }
    }
    /**
     * 
     * @param array $filtro
     * @param boolean $ashtml
     * @param boolean $values
     * @return mixed
     */
    public function getReporteByUsername(array $filtro = null, $ashtml = true, $values = true) {
        $rows = $this->em->getRepository('IneiPayrollBundle:PlanillaHistoricas')
                ->getByUsername($filtro);
        if ($ashtml) {
            $html = '';
            $color = array('info', '');
            foreach ($rows as $key => $value) {
                $html .="<tr class='" . $color[($key % 2)] . "'>";
                $html .='<td>' . $value['digitador'] . '</td>';
                $html .='<td>' . $value['tomo'] . '</td>';
                $html .='<td>' . $value['folios'] . '</td>';
                $html .='<td>' . $value['resumen'] . '</td>';
                $html .='<td>' . ($value['folios']-$value['resumen']) . '</td>';
                $html .='<td>' . $value['folios_digitados'] . '</td>';
                $html .='<td>' . $value['porcentaje_folios'] . '%</td>';
                $html .='<td>' . $value['registros'] . '</td>';
                $html .='<td>' . $value['digitados'] . '</td>';
                $html .='<td>' . $value['porcentaje_registros'] . '%</td>';
                $html .='</tr>';
            }
            $rows = $html;
        } else if ($values) {
            $rows = array_map(create_function('$item', 'return array_values($item);'), $rows);
        }
        return $rows;
    }

    /**
     * 
     * @param array $filtro
     * @param boolean $ashtml
     * @param boolean $values
     * @return mixed
     */
    public function getReporteByTomo(array $filtro = null, $ashtml = true, $values = true) {
        $rows = $this->em->getRepository('IneiPayrollBundle:Tomos')
                ->findResumenFolios($filtro);
        if ($values) {
            $rows = array_map(create_function('$item', 'return array_values($item);'), $rows);
        }
        return $rows;
    }
    
    /**
     * 
     * @param array $rows
     * @param array $cols
     * @param string $title
     * @param integer $from
     * @return \PHPExcel
     */
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
                $sheet->setCellValueByColumnAndRow($col, $row+$from,
                        ($col<$count)?$rcell[$col]:null);
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

}