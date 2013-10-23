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
    private static $REPORTES = array('digitador','tomo');
    /**
     * @Route("/reporte-tomo/", name="_planilla_tomo_reporte")
     * @Template("")
     */
    public function reporteTomoAction() {
        return array();
    }
        
    /**
     * @Route("/reporte-tomo/print/", name="_planilla_tomo_reporte_print")
     * @Template("")
     */
    public function printTomoReporteAction(Request $request) {
        $form = $request->request->get('form');
        if(!$form){
            $form = $request->query->get('form');
        }
        $service = $this->get('planilla_service');
        $data = $service->getReporteByTomo($form);
        $excel = $service->printReporte($data,array(
            'Numero de Tomo','Total de Folios','Folios de Resumen','Folios Digitables',
        'Folios Digitados','Folios por Digitar','Estado'),'Reporte de Avance por Tomos',4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xlsx"','reptomo'));
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        exit;        
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
        $service = $this->get('planilla_service');
        if ($object && $object->getRegistrosFolio()) {
            $_form = $this->createPlanillaForm(array(), $object);
            $_form->handleRequest($request);
            if ($_form->isValid()) {
                /**                 * *GUARDAR** */
                $_data = $_form->getData();
                $data = $_data['payrolls'];
                if ($service->saveMatrix($object, $data)) {
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Registro grabado satisfactoriamente'
                    );
                } else {
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Ocurrio un error al grabar la planilla'
                    );
                }
            }
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
        $folio = null;
        $tomo = null;
        $object = null;
        if ($request->request->get('form')) {
            $folio = $request->request->get('folio');
            $tomo = $request->request->get('tomo');
        } else if ($request->request->get('registrar_planilla')) {
            $aplanilla = $request->request->get('registrar_planilla');
            $folio = array_key_exists('folio', $aplanilla) ? $aplanilla['folio'] : null;
            $tomo = array_key_exists('tomo', $aplanilla) ? $aplanilla['tomo'] : null;
        }
        $form = null;
        $sform = $this->createForm('registrar_planilla', null, array('em' => $this->getDoctrine()->getManager()));
        $sform->handleRequest($request);
        if (null != $folio & null != $tomo) {
            $em = $this->getDoctrine()
                    ->getRepository('IneiPayrollBundle:Folios');
            $object = $em->findOneCustomByNum($folio, $tomo);
        }
        $service = $this->get('planilla_service');
        if ($object && $object->getRegistrosFolio()) {
            $array = $service->generateMatrix($object, true);
            $_form = $this->createPlanillaForm($array, $object);
            $form = $_form->createView();
        }
        return array(
            'form' => $form,
            'sform' => $sform->createView(),
            'folio' => $object
        );
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
        $title = sprintf('Planilla TOMO %s  FOLIO%s',$tomo,$folio);
        $excel = $service->printReporte($data,$cols,$title,4);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 
                sprintf('attachment;filename="%s_%s_%s.xlsx"','planilla',$tomo,$folio));
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
        $query = $em->findUsingLike($criteria, 'ORDER BY t.descSubtStp ASC');
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

}
