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
    
    public function getPlanillaColumns($object) {
        if (!$object)
            return array();
        $array = $this->em->getRepository('IneiPayrollBundle:ConceptosFolios')
                ->getForColumns($object->getCodiFolio());
        $result = array_map(
                create_function('$item', 'return $item["descCortTco"];'), $array);
        return array_merge(array('REGISTRO', 'NOMBRES Y APELLIDOS', 'OBSERVACION'), $result);
    }

    public function getPlanillaValues($object) {
        if (!$object)
            return array();
        $_planillas = $this->getPlanillas($object->getCodiFolio());
        $planilla = array();
        $array = array();
        $co = 0;
        if ($_planillas) {
            $reg = $_planillas[0]->getRegistro();
            foreach ($_planillas as $key => $value) {
                if ($reg == $value->getRegistro()) {
                    $reg = $value->getRegistro();
                    $planilla['registro'] = ($reg + 1);
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
                $html .='<td>' . ($value['folios'] - $value['resumen']) . '</td>';
                $html .='<td>' . $value['folios_digitados'] . '</td>';
                $html .='<td>' . $value['porcentaje_folios'] . '%</td>';
                $html .='<td>' . $value['registros'] . '</td>';
                $html .='<td>' . $value['digitados'] . '</td>';
                $html .='<td>' . $value['tregistros'] . '</td>';
                $html .='<td>' . $value['tdias'] . '</td>';
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
     * @param array $filtro
     * @param boolean $ashtml
     * @param boolean $values
     * @return mixed
     */
    public function getReporteByFolio(array $filtro = null, $ashtml = true, $values = true) {
        $rows = $this->em->getRepository('IneiPayrollBundle:Folios')
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
                $sheet->setCellValueByColumnAndRow($col, $row + $from, ($col < $count) ? $rcell[$col] : null);
            }
        }
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    /*     * *******************PRUEBAS ************************ */

    public function generateMatrix($object, $autosave = false) {
        $array = array('payrolls' => array_map(
                    create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
        $estado = 1;//1 = GRABADO, 2=AUTOGUARDADO
        $_planillas = $this->getPlanillas($object->getCodiFolio());
//        print_r(count($_planillas));exit;
        if (count($_planillas)===0) {
            $estado = 3;
            if ($autosave) {
                $_planillas = $this->
                        loadAutoSave($object->getTomo()->getCodiTomo(), $object->getFolio());
                if (null !== $_planillas) {
                    $plas = $_planillas['payrolls'];
                    foreach ($array['payrolls'] as $key => $value) {
                        $array['payrolls'][$key] = array_key_exists($key, $plas)?
                                $plas[$key]:array();
                    }
                    $estado = 2;
                    //return $array;
                }
            }
        }
        
        $planilla = array();
        $co = 0;
        if (count($_planillas) && $estado === 1) {
            $reg = $_planillas[0]->getRegistro();
            $codigos = array();
            foreach ($_planillas as $key => $value) {
                if ($reg == $value->getRegistro()) {
                    $reg = $value->getRegistro();
                    $codigos[] = $value->getId();
                    $planilla['registro'] = $reg;
                    $planilla['codiEmplPer'] = $value->getCodiEmplPer();
                    $planilla['descripcion'] = $value->getDescripcion();
                    $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                    $planilla[$key] = $value->getValoCalcPhi();
                    continue;
                }
                $planilla['codigos'] = implode(',', $codigos);
                $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                $array['payrolls'][$co] = $planilla;
                $reg = $value->getRegistro();
                $codigos = array();
                $codigos[] = $value->getId();
                $planilla['codiEmplPer'] = strtoupper($value->getCodiEmplPer());
                $planilla['descripcion'] = $value->getDescripcion();
                $planilla[$key] = $value->getValoCalcPhi();
                $co++;
                if ($co > $object->getRegistrosFolio() - 1)
                    break;
            }
            if ($co <= $object->getRegistrosFolio() - 1)
                $planilla['codigos'] = implode(',', $codigos);
            $array['payrolls'][$co] = $planilla;
        }
        return array(
            'data' => $array,
            'estado' => $estado
        );
    }

    public function saveMatrix($object, $data) {
        $conn = $this->em->getConnection();
        try {
            //FALTA JALAR LA FILA key para coger correctamente los ids
            //ACTUALIZAR EL VALOR SOLO SI ESTE CAMBIA
            $userid = $this->sc->getToken()->getUser()->getId();
            $conn->beginTransaction();
            $stmt = $conn->prepare(
                    'SELECT fn_planilla (:aid, :aano_peri_tpe, :anume_peri_tpe,
                                :avalo_calc_phi, :atipo_plan_tpl, :asubt_plan_stp, 
                                :acodi_empl_per, :acodi_conc_tco, :acodi_folio, 
                                :adesc_plan_stp, :aflag_folio, :anum_reg,
                                :ausu_crea_id, :ausu_mod_id)'
            );
            $_periodo = $object->getPeriodoFolio();
            $periodo = strlen($_periodo)<2?
                    str_pad($_periodo, 2, '0', STR_PAD_LEFT):
                    strlen($_periodo)===2?$_periodo:'00';
            foreach ($data as $key1 => $planilla) {
                if ($key1 >= $object->getRegistrosFolio())
                    break;
                $reg = $planilla['registro'];
                $dni = $planilla['codiEmplPer'];
                $descripcion = $planilla['descripcion'];
                $codigos = explode(',', $planilla['codigos']);
                unset($planilla['codiEmplPer']);
                unset($planilla['descripcion']);
                unset($planilla['registro']);
                unset($planilla['codigos']);
                $co = 0;
                foreach ($planilla as $key => $valor) {
                    $pos = strpos($key, '_');
                    $stmt->bindValue('aid', array_key_exists($co, $codigos) ?
                                    is_numeric($codigos[$co]) ? $codigos[$co] : null : null);
                    $stmt->bindValue('aano_peri_tpe', $object->getTomo()->getAnoTomo());
                    $stmt->bindValue('anume_peri_tpe', $periodo);
                    $stmt->bindValue('avalo_calc_phi', $valor);
                    $stmt->bindValue('atipo_plan_tpl', is_object($object->getTipoPlanTpl()) ? $object->getTipoPlanTpl()->getTipoPlanTpl() : $object->getTipoPlanTpl());
                    $stmt->bindValue('asubt_plan_stp', $object->getSubtPlanStp());
                    $stmt->bindValue('acodi_empl_per', $dni);
                    $stmt->bindValue('acodi_conc_tco', substr($key, 0, $pos));
                    $stmt->bindValue('acodi_folio', $object->getCodiFolio());
                    $stmt->bindValue('adesc_plan_stp', $descripcion);
                    $stmt->bindValue('aflag_folio', substr($key, $pos + 1));
                    $stmt->bindValue('anum_reg', $reg);
                    $stmt->bindValue('ausu_crea_id', $userid);
                    $stmt->bindValue('ausu_mod_id', $userid);
                    $stmt->execute();
                    $codigos[$co] = -1;
                    $co++;
                }
                /*                 * ***************ELIMINAMOS LAS FILAS QUE YA NO 
                 * SE ENCUENTREN EN LA MATRIZ******************* */
                $q = $this->em->createQuery('delete from 
                    IneiPayrollBundle:PlanillaHistoricas m where m.id in (:ids)');
                $q->setParameter('ids', $codigos);
                $q->execute();
            }
            $filename = $this->getAutoSaveFilename(
                    $object->getTomo()->getCodiTomo(), $object->getFolio());
            if (file_exists($filename)) {
                unlink($filename);
            }
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        } catch (\Exception $ee) {
            $conn->rollBack();
            return false;
        }
    }

}