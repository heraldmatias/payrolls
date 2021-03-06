<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\ExcelTomo;
use Symfony\Component\HttpFoundation\Response;
use \PHPExcel_IOFactory;
use Doctrine\DBAL\DBALException;

/**
 * ExcelTomo controller.
 *
 */
class ExcelTomoController extends Controller {

    /**
     * @Route("/", name="admin_excel_list")
     * @Template("")
     */
    public function listAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tomo_excel', 'query')) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('search_tomoexcel', null);
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:ExcelTomo');
        $query = $em->findBy($criteria, array(
            'title' => 'DESC'
        ));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/add", name="admin_excel_add")
     * @Template("")
     */
    public function newAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tomo_excel', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new ExcelTomo();
        $object->setCreador($this->get('security.context')->getToken()->getUser());
        $object->setCreatedAt(new \DateTime("now"));
        $form = $this->createForm('exceltomo', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                //$object->setRootDir( str_replace('/cards/app', $this->getRequest()->getBasePath(), $this->get('kernel')->getRootDir()).'/' );
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'exceltomo', 'Registro grabado satisfactoriamente'
                );
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'exceltomo', 'Ocurrio un error al grabar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? 'admin_excel_add' : 'admin_excel_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{pk}", name="admin_excel_edit")
     * @Template("")
     */
    public function editAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('tomo_excel', 'edit')) {
            throw $this->createNotFoundException();
        }
        $object = $this->getDoctrine()->getRepository('IneiPayrollBundle:ExcelTomo')->find($pk);
        if (!$object) {
            throw $this->createNotFoundException('Archivo no encontrado ' . $pk);
        }
        $form = $this->createForm('exceltomo', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setUpdatedAt(new \DateTime("now"));
                $object->setModificador($this->get('security.context')->getToken()->getUser());
                $object->uploadFile();
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'exceltomo', 'Registro modificado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'exceltomo', 'Ocurrio un error al modificar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? 'admin_excel_add' : 'admin_excel_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/delete/{pk}", name="admin_excel_delete")
     * @Template("")
     */
    public function deleteAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('tomo_excel', 'del')) {
            throw $this->createNotFoundException();
        }
        try {
            $object = $this->getDoctrine()->getRepository('IneiPayrollBundle:ExcelTomo')->find($pk);
            if (!null == $object->getTomo()) {
                $tomo = $this->getDoctrine()->getRepository('IneiPayrollBundle:Tomos')->find($object->getTomo());
                if (null !== $tomo) {
                    $emt = $this->getDoctrine()->getEntityManager();
                    $emt->remove($tomo);
                    $emt->flush();
                }
            }
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($object);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                    'exceltomo', 'Registro eliminado satisfactoriamente'
            );
        } catch (DBALException $e) {
            $this->get('session')->getFlashBag()->add(
                    'exceltomo', 'Ocurrio un error al eliminar el registro'
            );
        }
        $nextAction = 'admin_excel_list';
        return $this->redirect($this->generateUrl($nextAction));
    }

    function getUniqueConceptos($sheet, $colc, $filaf, $folio) {
        $data = array();
        while (true) {
            $conc = $sheet->getCellByColumnAndRow($colc, $filaf)->getValue();
            if (null === $conc | '' === trim($conc))
                break;
            $_conc = str_replace(' ', '', strtoupper($conc));
            $data[] = sprintf("(%s, %s, '%s')", $colc - 3, $folio, $_conc);
            $colc++;
        }
        return implode(',', $data);
    }
    /* CONCEPTO NUEVO                       00B0F0
     * CONCEPTO MODIFICADO                  FFFF00
     * CONCEPTO SIN CAMBIOS O ELIMINADO     000000
     */
    function getUniqueArrayConceptos($sheet, $colc, $filaf) {
        $data = array();
        $tipo = -1;
        while (true) {
            $color = $sheet->getStyleByColumnAndRow($colc, $filaf)->getFill()->getStartColor()->getRGB();
            switch ($color) {
                case '000000':
                    $tipo = 1;/*CONCEPTO SIN CAMBIOS O ELIMINADO     000000*/
                    break;
                case '00B0F0':
                    $tipo = 2;/*CONCEPTO NUEVO                       00B0F0*/
                    break;
                case 'FFFF00':
                    $tipo = 3;/*CONCEPTO MODIFICADO                  FFFF00*/
                    break;
            }
            $conc = $sheet->getCellByColumnAndRow($colc, $filaf)->getValue();
            if (null === $conc | '' === trim($conc))
                break;
            $_conc = str_replace(' ', '', strtoupper($conc));
            $data[] = array($tipo, $_conc);
            $colc++;
        }
        return $data;
    }

    /**
     * @Route("/process/", name="admin_excel_process")
     * @Template("")
     */
    public function processAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tomo_excel', 'other')) {
            throw $this->createNotFoundException();
        }
        $pk = $request->query->get('pk');
        $data = array('success' => false, 'error' => NULL, 'data' => NULL);
        $conn = $this->get('database_connection');
        /*         * 0 BASED INDEX
         * C F
         * 0, 1 => TITULO
         * 0, 6 => CABECERA
         * 0, 7 => EMPIEZA LOS DATOS
         *
         * FILA 4 => DATOS DEL TOMO
         * 1, 4 => PERIODO DEL TOMO
         * 4, 4 => ANO DEL TOMO
         * 6, 4 => CODIGO DEL TOMO
         * 8, 4 => FOLIOS DEL TOMO
         *
         * FILA 7 => EMPIEZAN LOS VALORES
         * COL 0 => FOLIO
         * COL 1 => PERIODO
         * COL 2 => REGISTRO
         * COL 3 => TIPO
         * COL 4 => EMPIEZAN LOS CAMPOS
         */
        /*         * ****VARIABLES PARA OBTENER LAS CELDAS CON LOS DATOS DEL ARCHIVO EXCEL***************** */
        $filat = 4; /* EN ESTA FILA EMPIEZAN LOS DATOS DE LOS TOMOS* */
        $filaf = 7; /* EN ESTA FILA EMPIEZAN LOS DATOS DE LOS FOLIOS* */
        $colc = 4; /* EN ESTA COLUMNA EMPIEZAN LOS CONCEPTOS DE LOS FOLIOS* */
        $userid = $this->get('security.context')
                        ->getToken()->getUser()->getId();
        /*         * *************************SE GUARDA EL TOMO********************* */
        $sptomo = 'SELECT fn_tomo(:tomo, :periodo, :ano, :folios, :desc, :usuario);';
        $insertFolio = 'SELECT fn_folio(
:aper_folio, :areg_folio, :asubt_plan_stp, :acodi_tomo, :atipo_plan_tpl,
:anum_folio,:ausu_crea_id) as pk;';
        $insertConceptos = 'insert into conceptos_folios(orden_conc_folio,
codi_folio, codi_conc_tco) values ';
        $updateConceptos = 'update conceptos_folios SET orden_conc_folio = :orden,
            codi_conc_tco=:concepto WHERE id = :folio';
        $deleteConceptos = 'DELETE FROM conceptos_folios WHERE id = :folio';
        
        try {

            $object = $this->getDoctrine()->getRepository('IneiPayrollBundle:ExcelTomo')->find($pk);
            $objPHPExcel = PHPExcel_IOFactory::load($object->getFullPath());
            $sheet = $objPHPExcel->getSheet(0);
            $tomo = $sheet->getCellByColumnAndRow(4, $filat)->getValue();
            $errors = $this->validateTomoExcel($sheet, $filaf, $colc, $filat, $tomo);
            if(count($errors)>0){
                $msgerror = implode('<br>', $errors);
                throw new \Exception($msgerror);
            }
            $conn->beginTransaction();
            $folios = $sheet->getCellByColumnAndRow(6, $filat)->getValue();
            $nfolio = 1;
            $stmt = $conn->prepare($sptomo);
            $stmt->bindValue(1, $tomo);
            $stmt->bindValue(2, ucwords($sheet->getCellByColumnAndRow(2, $filat)->getValue()));
            $stmt->bindValue(3, $sheet->getCellByColumnAndRow(1, $filat)->getValue());
            $stmt->bindValue(4, $folios);
            $stmt->bindValue(5, NULL);            
            $stmt->bindValue(6, $userid);
            $stmt->execute();
        /*PROCESO QUE LEE LOS FOLIOS Y CONCEPTOS DESDE LA FILA 4 PARA INSERTAR O ACTUALIZAR EN LA BD*/
            while ($nfolio <= $folios) {
                $registros = $sheet->getCellByColumnAndRow(2, $filaf)->getValue();
                $tplanilla = $sheet->getCellByColumnAndRow(3, $filaf)->getValue();
                $periodo = $sheet->getCellByColumnAndRow(1, $filaf)->getValue();
                /*BUSCAR SI HAY DIFERENCIA ENTRES LOS FOLIOS*/
                $stmt = $conn->prepare($insertFolio);
                $stmt->bindValue(1, is_numeric($periodo) & strlen($periodo) === 1 ?
                        str_pad($periodo, 2, '0', STR_PAD_LEFT):$periodo);
                $stmt->bindValue(2, $registros ? $registros : NULL);
                $stmt->bindValue(3, NULL);
                $stmt->bindValue(4, $tomo);
                $stmt->bindValue(5, $tplanilla ? trim(str_replace(' ', '', strtoupper($tplanilla))) : NULL);
                $stmt->bindValue(6, $nfolio);
                $stmt->bindValue(7, $userid);
                $stmt->execute();
                $_folio = $stmt->fetch();
                $folio = $_folio['pk'];
                if ($folio > 0) {                    
                    /**
                     * Si el folio no existe entonces los conceptos 
                     * de los folios tampoco y se deben insertar a la base de datos
                     **/
                    $conceptos = $this->getUniqueConceptos($sheet, $colc, $filaf, $folio);
                    if ($conceptos) {
                        $stmt2 = $conn->prepare($insertConceptos . $conceptos);
                        $stmt2->execute();
                    }                    
                }else{
                    /**
                     * Si el folio existe entonces se deben reemplazar los conceptos 
                     * en el mismo orden que se van encontrando en el inventario
                     * la diferencia entre los que hay actualmente y los nuevos debe
                     * eliminarse
                    **/
                    $conceptos = $this->getUniqueArrayConceptos($sheet, $colc, $filaf);
                    $_data = $this->getDoctrine()->getRepository('IneiPayrollBundle:Folios')
                            ->findCustomByNum($nfolio, $object->getTomo());
                    $conceptosdb = $_data['conceptos'];
                    $codifolio = $_data['folio'];
                    $dbCounter = 0;
                    $supd = $conn->prepare($updateConceptos);
                    $sdel = $conn->prepare($deleteConceptos);
                    $i = count($conceptos);
                    $k = count($conceptosdb);
                    foreach ($conceptos as $order => $concepto) {
                        if($concepto[0] === 1){
                            if($dbCounter<$k){
                                if($concepto[1] !== $conceptosdb[$dbCounter][0]){
                                    $sdel->bindValue(1, $conceptosdb[$dbCounter][1]);
                                    $sdel->execute();
                                }else{
                                    $supd->bindValue(1, ($order+1));
                                    $supd->bindValue(2, $concepto[1]);
                                    $supd->bindValue(3, $conceptosdb[$dbCounter][1]);
                                    $supd->execute();
                                }
                                unset($conceptosdb[$dbCounter]);
                                $dbCounter++;
                            }
                        }else if($concepto[0] === 2){
                            $conn->prepare($insertConceptos . sprintf("(%s, %s, '%s')", ($order+1), $codifolio, $concepto[1]))->execute();
                        }else if($concepto[0] === 3){
                            if($dbCounter<$k){
                                $supd->bindValue(1, ($order+1));
                                $supd->bindValue(2, $concepto[1]);
                                $supd->bindValue(3, $conceptosdb[$dbCounter][1]);
                                $supd->execute();
                                unset($conceptosdb[$dbCounter]);
                                $dbCounter++;
                            }
                        }
                    }
                    
                    if($k>$i){
                        /*borrar los que han sido quitados del excel*/
                        for ($index = $i; $index < $k; $index++) {
                            $concepto = $conceptosdb[$index];
                            $sdel->bindValue(1, $concepto[1]);
                            $sdel->execute();
                        }
                    }
                }
                $filaf++;
                $nfolio++;
            }
            $data['success'] = true;
            $data['data'] = 'Almacenado con exito';
            $conn->commit();
            $this->updateTomo($object, $tomo, $data['data']);
        } catch (DBALException $e) {
            $data['error'] = "Ocurrio un error al grabar a la Base de Datos <br> El sistema devolvio el siguiente mensaje <br>". $e->getMessage();
            $conn->rollback();
            if (isset($object)) {
                $this->updateTomo($object, null, $data['error']);
            }
        } catch (\Exception $e) {
            $data['error'] = "Por favor corrija la(s) siguiente(s) fila(s) <br>" . $e->getMessage();
            if (isset($object)) {
                $this->updateTomo($object, null, $data['error']);
            }
        }
        $conn->close();

        $response = new Response(json_encode($data));
        $response->headers->set('content-type', 'application/json');
        return $response;
    }

    private function updateTomo($object, $tomo, $msg) {
        if (null != $tomo)
            $object->setTomo($tomo);
        if (null !== $msg)
            $object->setDescription($msg);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($object);
        $em->flush();
    }
    
    private function validateTomoExcel($sheet, $filaf, $colc, $filat, $tomo){
        $folios = $sheet->getCellByColumnAndRow(6, $filat)->getValue();
        $nfolio = 1;
        $errors = array();
        $_conceptos = $this->getDoctrine()->getManager()->createQuery(
                        'SELECT c.codiConcTco FROM IneiPayrollBundle:Conceptos c'
                )->getArrayResult();
        $conceptos = array_map(
                create_function('$item', 'return strtolower($item["codiConcTco"]);'), $_conceptos);
        $conceptos[] = null;
        $_planillas = $this->getDoctrine()->getManager()->createQuery(
                        'SELECT c.tipoPlanTpl FROM IneiPayrollBundle:Tplanilla c'
                )->getArrayResult();
        $planillas = array_map(
                create_function('$item', 'return strtolower($item["tipoPlanTpl"]);'), $_planillas);
        for ($nfolio = 1; $nfolio <= $folios; $nfolio++) {
            $_colc = $colc;
            $registros = $sheet->getCellByColumnAndRow(2, $filaf)->getValue();
            $periodo = $sheet->getCellByColumnAndRow(1, $filaf)->getValue();
            $tplanilla = trim(strtolower($sheet->
                    getCellByColumnAndRow(3, $filaf)->getValue()));
            //$periodo = strtolower($sheet->getCellByColumnAndRow(1, $filaf)->getValue());            
            while (true) {
                $conc = $sheet->
                        getCellByColumnAndRow($_colc, $filaf)->getValue();
                if (null === $conc | '' === trim($conc))
                    break;
                $_conc = str_replace(' ', '', strtolower($conc));
                if(!in_array($_conc, $conceptos)){
                    $errors[] = sprintf('Fila: %s, Campo: Campo%s', $filaf, $_colc-3);
                }
                $_colc++;
            }
            /*SI HAY CONCEPTOS*/
            if($_colc !== $colc){
                /*VALIDA SI EXISTE EL CONCEPTO*/
                if(!is_numeric($registros) | $registros <=0 ){
                    $errors[] = sprintf('Fila: %s, Campo: Columna %s', $filaf, 'C');
                }
                /*VALIDA SI EXISTE LA PLANILLA*/
                if(!in_array($tplanilla, $planillas)){
                    $errors[] = sprintf('Fila: %s, Campo: Columna %s', $filaf, 'D');
                }
            }
            /*VALIDA QUE EL PERIDO SEA CORRECTO*/
            if($tomo > 88){
                if(!is_numeric($periodo) | strlen($periodo)>2){
                    if((is_numeric($registros) | $registros <=0) & (in_array($tplanilla, $planillas))){
                        $errors[] = sprintf('Fila: %s, Campo: Columna %s', $filaf, 'B');
                    }
                }
            }else{
                if($periodo === null | $periodo ===''){
                    $errors[] = sprintf('Fila: %s, Campo: Columna %s', $filaf, 'B');
                }
            }
            $filaf++;
        }
        return $errors;
    }

}