<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Inei\Bundle\PayrollBundle\Form\Type\PlanillaType;
use Inei\Bundle\PayrollBundle\Entity\Tplanilla;
use Inei\Bundle\PayrollBundle\Entity\Subtplanilla;

/**
 * Description of InventarioController
 *
 * @author holivares
 */
class PlanillaController extends Controller {

    private static $REPORTES = array('digitador', 'tomo');
    
    /**
     * @Route("/migracion/", name="_planilla_migrar")
     * @Template("")
     */
    public function migracionAction() {
        return array();
    }
    
    /**
     * @Route("/migracion/inicia/", name="_planilla_migrar_inicia")
     * @Template("")
     */
    public function migracionIniciaAction() {
        $service = $this->get('migracion_service');
        $data = $service->process();
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/migracion/estado/", name="_planilla_migrar_estado")
     * @Template("")
     */
    public function migracionEstadoAction() {
        $service = $this->get('migracion_service');
        $data = $service->get_proc_status();
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/migracion/mensajes/", name="_planilla_migrar_mensajes")
     * @Template("")
     */
    public function migracionMensajeAction() {
        $service = $this->get('migracion_service');
        $data = $service->get_log_messages();
        $response = new Response($data);
        return $response;
    }

    /**
     * @Route("/reporte-tomo/", name="_planilla_tomo_reporte")
     * @Template("")
     */
    public function reporteTomoAction() {
        return array();
    }

    /**
     * @Route("/reporte-tomo/ajax/", name="_planilla_tomo_ajax")
     * @Template("")
     */
    public function ajaxReporteTomoAction(Request $request) {
        $data = $request->request->get('form');
        $msg = $this->get('planilla_service')->getReporteByTomo($data);
        $response = new Response(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/reporte-tomo/print/", name="_planilla_tomo_reporte_print")
     * @Template("")
     */
    public function printTomoReporteAction(Request $request) {
        $form = $request->request->get('form');
        if (!$form) {
            $form = $request->query->get('form');
        }
        $service = $this->get('planilla_service');
        $data = $service->getReporteByTomo($form);
        $excel = $service->printReporte($data, array(
            'Numero de Tomo', 'Total de Folios', 'Folios de Resumen', 'Folios Digitables',
            'Folios Digitados', 'Folios por Digitar', 'Total Registros', 'Estado'), 'Reporte de Avance por Tomos', 4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xlsx"', 'reptomo'));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * @Route("/reporte-folio/", name="_planilla_folio_reporte")
     * @Template("")
     */
    public function reporteFolioAction() {
        return array();
    }

    /**
     * @Route("/reporte-folio/ajax/", name="_planilla_folio_ajax")
     * @Template("")
     */
    public function ajaxReporteFolioAction(Request $request) {
        $data = $request->request->get('form');
        $msg = $this->get('planilla_service')->getReporteByFolio($data);
        $response = new Response(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/reporte-folio/print/", name="_planilla_folio_reporte_print")
     * @Template("")
     */
    public function printFolioReporteAction(Request $request) {
        $form = $request->request->get('form');
        if (!$form) {
            $form = $request->query->get('form');
        }
        $service = $this->get('planilla_service');
        $data = $service->getReporteByFolio($form);
        $excel = $service->printReporte($data, array(
            'Digitador', 'Numero de Folios', 'Total Registros',
            'Registros Digitados', 'Fecha', 'Estado'), 'Reporte de Avance por Folios', 4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xlsx"', 'repfolio'));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /*REPORTE DE CONSOLIDADO DE TOMOS*/
    /**
     * @Route("/reporte-totales/", name="_planilla_totales_reporte")
     * @Template("")
     */
    public function reporteTotalesAction() {
        return array();
    }

    /**
     * @Route("/reporte-totales/ajax/", name="_planilla_totales_ajax")
     * @Template("")
     */
    public function ajaxReporteTotalesAction(Request $request) {
        $msg = $this->get('planilla_service')->getReporteTotal();
        $response = new Response(json_encode($msg));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/reporte-totales/print/", name="_planilla_totales_reporte_print")
     * @Template("")
     */
    public function printTotalesReporteAction(Request $request) {        
        $service = $this->get('planilla_service');
        $data = $service->getReporteTotal();
        $excel = $service->printReporte($data, array(
            'Estado', 'Tomos', 'Total de Folios','Total de Folios Resumen', 'Total de Folios Digitables',
             'Total de Folios Digitados', 'Total de Folios Digitar', 'Total Registros'), 'Reporte de Resumen', 4, true);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xlsx"', 'tomos_digitados'));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * @Route("/reporte/", name="_planilla_digitador")
     * @Template("")
     */
    public function reporteDigitadorAction() {
        return array();
    }

    /**
     * @Route("/reporte/ajax/", name="_planilla_digitador_ajax")
     * @Template("")
     */
    public function ajaxReporteDigitadorAction(Request $request) {
        $data = $request->request->get('form');
        $msg = $this->get('planilla_service')->getReporteByUsername($data);
        $response = new Response($msg);
        return $response;
    }

    /**
     * @Route("/reporte/print/", name="_planilla_digitador_ajax_print")
     * @Template("")
     */
    public function printReporteDigitadorAction(Request $request) {
        $form = $request->request->get('form');
        if (!$form) {
            $form = $request->query->get('form');
        }
        $service = $this->get('planilla_service');
        $data = $this->get('planilla_service')->getReporteByUsername($form, false);
        $excel = $service->printReporte($data, array(
            'Digitador', 'Tomo', 'Total Folios', 'Folios No Digitables',
            'Folios Digitables', 'Folios Digitados', '% Avance en Folios', 'Total de Registros',
            'Registros Digitados (Por Fecha)', 'Registros Digitados (Acumulado)',
            'Días Empleados', '% Avance en Registros'), 'Reporte de Avance por Digitador', 4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xlsx"', 'repfolio'));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * @Route("/autosave/", name="_planilla_autosave")
     * @Template("")
     */
    public function autoSaveAction(Request $request) {
        $data = $request->request->get('form');
        $tomo = $request->request->get('tomo');
        $folio = $request->request->get('folio');
        $msg = $this->get('planilla_service')->autoSave($tomo, $folio, $data);
        $response = new Response($msg);
        return $response;
    }

    /**
     * @Route("/print/", name="_planilla_print")
     * @Template("")
     */
    public function printAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('planilla', 'other')) {
            throw $this->createNotFoundException();
        }
        $form = $request->query->get('registrar_planilla');
        $folio = $form['folio'];
        $tomo = $form['tomo'];
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Folios');
        $object = $em->findOneCustomByNum($folio, $tomo);
        $service = $this->get('planilla_service');
        $data = $service->getPlanillaValues($object);
        $cols = $service->getPlanillaColumns($object);
        $title = sprintf('Planilla TOMO %s  FOLIO%s', $tomo, $folio);
        $excel = $service->printReporte($data, $cols, $title, 4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s_%s_%s.xlsx"', 'planilla', $tomo, $folio));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * @Route("/tplanilla/ajax/", name="_planilla_tplanilla_ajax")
     * @Template("")
     */
    public function tplanillaAjaxAction(Request $request) {
        $pla = $request->query->get('pla');
        $qb = $this->getDoctrine()
                ->getManager()->createQuery(
                        "SELECT s.subtPlanStp, s.descSubtStp FROM IneiPayrollBundle:Subtplanilla s 
                        WHERE s.tipoPlanTpl = :pla ")
                ->setParameters(array(
            'pla' => $pla,
        ));
        $response = new Response(json_encode($qb->getResult()));
        $response->headers->set('content-type', 'application/json');
        return $response;
    }

    /**
     * @Route("/tplanilla/", name="_planilla_tplanilla_list")
     * @Template("")     
     */
    public function tplanillaAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tplanilla', 'query')) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('search_tplanilla', null);
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tplanilla');
        $query = $em->findUsingLike($criteria, 'order by t.descTipoTpl ASC');
//        $query = $em->findBy(array(), array(
//            'descTipoTpl' => 'ASC'
//        ));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 15
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/tplanilla/add/", name="_planilla_tplanilla_add")
     * @Template("")
     */
    public function addTplanillaAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('tplanilla', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new Tplanilla();
        $form = $this->createForm('tplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setCreador($this->get('security.context')->getToken()->getUser());
                $object->setFecCreac(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'tplanilla', 'Registro grabado satisfactoriamente'
                );
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'tplanilla', 'Ocurrio un error al grabar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_planilla_tplanilla_add' : '_planilla_tplanilla_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/tplanilla/{pk}", name="_planilla_tplanilla_edit")
     * @Template("")     
     */
    public function editTplanillaAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('tplanilla', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tplanilla');
        $object = $em->find($pk);
        $form = $this->createForm('tplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setModificador($this->get('security.context')->getToken()->getUser());
                $object->setFecMod(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'tplanilla', 'Registro modificado satisfactoriamente'
                );
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'tplanilla', 'Ocurrio un error al modificar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_planilla_tplanilla_add' : '_planilla_tplanilla_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/subtplanilla/", name="_planilla_subtplanilla_list")
     * @Template("")
     */
    public function subtplanillaAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('subtplanilla', 'query')) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('search_subtplanilla', null, array(
            'method' => 'GET',
        ));
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Subtplanilla');
        $query = $em->findUsingLike($criteria, 'ORDER BY st.descSubtStp ASC');
//        $query = $em->findBy(array(), array(
//            'descSubtStp' => 'ASC'
//        ));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 15
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/subtplanilla/add/", name="_planilla_subtplanilla_add")
     * @Template("")
     */
    public function addSubtplanillaAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('subtplanilla', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new Subtplanilla();
        $form = $this->createForm('subtplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setCreador($this->get('security.context')->getToken()->getUser());
                $object->setFecCreac(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'subtplanilla', 'Registro grabado satisfactoriamente'
                );
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'subtplanilla', 'Ocurrio un error al grabar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_planilla_subtplanilla_add' : '_planilla_subtplanilla_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/subtplanilla/{planilla}/{pk}", name="_planilla_subtplanilla_edit")
     * @Template("")
     */
    public function editSubtplanillaAction(Request $request, $planilla, $pk) {
        if (!$this->get('usuario_service')->hasPermission('subtplanilla', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Subtplanilla');
        $object = $em->findOneBy(array(
            'tipoPlanTpl' => $planilla,
            'subtPlanStp' => $pk
        ));
        $form = $this->createForm('subtplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $object->setModificador($this->get('security.context')->getToken()->getUser());
                $object->setFecMod(new \DateTime());
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'subtplanilla', 'Registro modificado satisfactoriamente'
                );
            } catch (Doctrine\DBAL\DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'subtplanilla', 'Ocurrio un error al modificar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_planilla_subtplanilla_add' : '_planilla_subtplanilla_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /*     * *************************PRUEBAS********************************** */

    public function createPlanillaForm($array, $object) {
        return $this->createFormBuilder($array, array(
                            'attr' => array(
                                'id' => 'form_planilla'
                            ),
                            'action' => $this->generateUrl('_planilla_save')
                        ))
                        ->add('payrolls', 'collection', array(
                            'required' => true,
                            'allow_delete' => true,
                            'allow_add' => true,
                            'prototype' => true,
                            'type' => new PlanillaType(),
                            'options' => array(
                                'folio' => $object
                            )
                        ))
                        ->add('save', 'submit', array(
                            'label' => 'Guardar',
                            'attr' => array('class' => 'btn btn-primary'),))
                        ->getForm();
    }

    /**
     * @Route("/save", name="_planilla_save")
     * @Template("")
     */
    public function saveAction(Request $request) {
        $object = null;
        $folio = $request->request->get('folio');
        $tomo = $request->request->get('tomo');
        if (null != $folio & null != $tomo) {
            $em = $this->getDoctrine()
                    ->getRepository('IneiPayrollBundle:Folios');
            $object = $em->findOneCustomByNum($folio, $tomo);
        }
        $data = array();
        if ($request->request->has('form')) {
            $form = $request->request->get('form');
            if (array_key_exists('payrolls', $form)) {
                $data = $form['payrolls'];
            }
        }
        $service = $this->get('planilla_service'); //SERVICIO
        $session = $this->get('session'); //SESSION
        if ($object && $object->getRegistrosFolio()) {
            /*
             * AL GUARDAR LA PLANILLA SE ALMACENA EL NUMERO DE TOMO
             * PARA LUEGO OBTENER EL SIGUIENTE FOLIO A DIGITAR EN
             * DICHO TOMO
             */
            if ($service->saveMatrix($object, $data)) {
                $session->set('tomo', $tomo);
                $session->getFlashBag()->add(
                        'planilla', 'Registro grabado satisfactoriamente'
                );
            } else {
                $this->get('session')->getFlashBag()->add(
                        'planilla', 'Ocurrio un error al grabar la planilla'
                );
            }
        }
        /*
         * OBTIENE EL SIGUIENTE FOLIO DIGITABLE EN EL TOMO
         * EN CASO NO EXISTA MAS FOLIOS DIGITABLES CON RESPECTO AL
         * FOLIO ACTUAL, EL USUARIO ES LIBRE DE ELEGIR OTRO TOMO Y FOLIO 
         * MOTIVO POR EL CUAL SE DESTRUYEN LAS VARIABLES DE SESION
         */
        $nextFolio = $folio + 1; //$this->get('folios_service')->
        //siguienteFolioDigitable($tomo, $folio);
        $session->set('folio', $nextFolio);
        if (!$nextFolio) {
            $session->remove('tomo');
            $session->remove('folio');
        }
        $nextAction = '_planilla_add';
        return $this->redirect($this->generateUrl($nextAction));
    }

    /**
     * @Route("/add", name="_planilla_add")
     * @Template("")
     */
    public function addUpdateAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('planilla', 'query')) {
            throw $this->createNotFoundException();
        }
        $session = $this->get('session');
        $form = null;
        $folio = null;
        $tomo = null;
        $object = null;
        /*
         * SE BUSCA EL NUMERO DE TOMO Y NUMERO DE FOLIO DESDE EL FORMULARIO
         * EN CASO NO SE HAYA ELEGIDO NINGUNO, SE PROCEDERA A BUSCAR EN LAS
         * VARIABLES DE SESION PARA AUTOSELECCIONAR EL FOLIO SIGUIENTE, EN
         * CASO EXISTA
         */
        if ($request->request->get('form')) {
            $folio = $request->request->get('folio');
            $tomo = $request->request->get('tomo');
        } else if ($request->request->get('registrar_planilla')) {
            $aplanilla = $request->request->get('registrar_planilla');
            $folio = array_key_exists('folio', $aplanilla) ? $aplanilla['folio'] : null;
            $tomo = array_key_exists('tomo', $aplanilla) ? $aplanilla['tomo'] : null;
        }
        if (null === $folio & null === $tomo) {
            $folio = $session->get('folio');
            $tomo = $session->get('tomo');
        }
        if (null != $folio & null != $tomo) {
            $em = $this->getDoctrine()
                    ->getRepository('IneiPayrollBundle:Folios');
            $object = $em->findOneCustomByNum($folio, $tomo);
        }
        $service = $this->get('planilla_service');
        /*         * **************FORMULARIOS*********************** */
        $sform = $this->createForm('registrar_planilla', null, array('em' => $this->getDoctrine()->getManager(),
            'tomo' => $tomo,
            'folio' => $folio));
        $sform->handleRequest($request);
        $estado = null;
        if ($object && $object->getRegistrosFolio()) {
            $array = $service->generateMatrix($object, true);
            $_form = $this->createPlanillaForm($array['data'], $object);
            $form = $_form->createView();
//            $estado = $array['estado']===1?'PLANILLA GUARDADA EN LA BASE DE DATOS':
//                      ($array['estado']===2?'PLANILLA GUARDADA TEMPORALMENTE':
//                      'PLANILLA POR DIGITAR');
            $estado = $array['estado'];
        }
        $view = 'IneiPayrollBundle:Planilla:addUpdateOld.html.twig';
        if ($tomo >= 89)
            $view = 'IneiPayrollBundle:Planilla:addUpdate.html.twig';
        return $this->render($view, array(
                    'form' => $form,
                    'sform' => $sform->createView(),
                    'folio' => $object,
                    'estado' => $estado
        ));
    }

}
