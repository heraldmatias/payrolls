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
        $object->setCreatedAt(new \DateTime("now"));
        $form = $this->createForm('exceltomo', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                //$object->setRootDir( str_replace('/cards/app', $this->getRequest()->getBasePath(),  $this->get('kernel')->getRootDir()).'/' );
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

                $emt = $this->getDoctrine()->getEntityManager();
                $emt->remove($tomo);
                $emt->flush();
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
//        $_conceptos = array_count_values($data);
//        $colc = 1;
//        $conceptos = array();
//        foreach ($_conceptos as $key => $value) {
//            $conceptos[] = sprintf("(%s, %s, '%s', %s)", $colc, $folio, $key, $value);
//            $colc++;
//        }
        return implode(',', $data);
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
         * C  F
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
        /*         * *************************SE GUARDA EL TOMO********************* */
        $insertFolio = 'INSERT INTO folios(
            per_folio, reg_folio, subt_plan_stp, codi_tomo, tipo_plan_tpl, 
            num_folio) VALUES ( ?, ?, ?, ?, ?, ?) returning codi_folio;';
        $insertConceptos = 'insert into conceptos_folios(orden_conc_folio,
            codi_folio, codi_conc_tco) values ';
        try {
            $object = $this->getDoctrine()->getRepository('IneiPayrollBundle:ExcelTomo')->find($pk);
            if (!null == $object->getTomo()) {
                $tomo = $this->getDoctrine()->getRepository('IneiPayrollBundle:Tomos')->find($object->getTomo());
                $emt = $this->getDoctrine()->getEntityManager();
                if (null !== $tomo) {
                    $emt->remove($tomo);
                    $emt->flush();
                }
            }
            $objPHPExcel = PHPExcel_IOFactory::load($object->getFullPath());
            $sheet = $objPHPExcel->getSheet(0);
            $conn->beginTransaction();
            $tomo = $sheet->getCellByColumnAndRow(4, $filat)->getValue();
            $conn->insert('tomos', array(
                'codi_tomo' => $tomo,
                'per_tomo' => ucwords($sheet->getCellByColumnAndRow(2, $filat)->getValue()),
                'ano_tomo' => $sheet->getCellByColumnAndRow(1, $filat)->getValue(),
                'folios_tomo' => $sheet->getCellByColumnAndRow(6, $filat)->getValue(),
                'desc_tomo' => NULL
            ));
            while (true) {
                $nfolio = $sheet->getCellByColumnAndRow(0, $filaf)->getValue();
                $registros = $sheet->getCellByColumnAndRow(2, $filaf)->getValue();
                $tplanilla = $sheet->getCellByColumnAndRow(3, $filaf)->getValue();
                if (null === $nfolio)
                    break;
                $stmt = $conn->prepare($insertFolio);
                $stmt->bindValue(1, ucwords($sheet->getCellByColumnAndRow(1, $filaf)->getValue()));
                $stmt->bindValue(2, $registros ? $registros : NULL);
                $stmt->bindValue(3, NULL);
                $stmt->bindValue(4, $tomo);
                $stmt->bindValue(5, $tplanilla ? str_replace(' ', '', strtoupper($tplanilla)) : NULL);
                $stmt->bindValue(6, $nfolio);
                $stmt->execute();
                $folio = $stmt->fetch()['codi_folio'];
                $conceptos = $this->getUniqueConceptos($sheet, $colc, $filaf, $folio);
                if ($conceptos) {
                    $stmt2 = $conn->prepare($insertConceptos . $conceptos);
                    $stmt2->execute();
                }
                $filaf++;
            }
            $data['success'] = true;
            $data['data'] = 'Almacenado con exito';
            $conn->commit();
            $this->updateTomo($object, $tomo, $data['data']);
        } catch (DBALException $e) {
            $data['error'] = "Ocurrio un error al grabar a la Base de Datos \nRevise la fila $filaf y la Columna $colc";
            $conn->rollback();
            if (isset($object)) {
                $this->updateTomo($object, null, $data['error']);
            }
        } catch (\Exception $e) {
            $data['error'] = "Ocurrio un error inesperado " . $e->getMessage() . " \nRevise la fila $filaf y la Columna $colc";
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

}
