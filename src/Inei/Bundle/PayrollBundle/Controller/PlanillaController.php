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

    /**
     * @Route("/add/", name="_planilla_add")
     * @Template("")
     */
    public function addAction(Request $request) {
        $pk = null;$object = null;
        if($request->request->get('form') ){
            $pk = $request->request->get('folio');
        }else if($request->request->get('registrar_planilla')){
            $pk = array_key_exists('folio',$request->request->get('registrar_planilla'))?$request->request->get('registrar_planilla')['folio']:null;
        }
        $form = null;
        $sform = $this->createForm('registrar_planilla', null, array('em' => $this->getDoctrine()->getManager()));
        $sform->handleRequest($request);
        
        if($pk){
            $em = $this->getDoctrine()
                    ->getRepository('IneiPayrollBundle:Folios');
            $object = $em->findOneCustomBy(array('folio' => $pk)); 
        }        
        if ($object) {
            
            $_planillas = $object->getPlanillas($this->getDoctrine()->getManager());
            $planilla = array();
            $array = array('payrolls' => array_map(
                        create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
            $co = 0;
            if ($_planillas) {
                //$array = array('payrolls' => null);
                $dni = $_planillas[0]->getCodiEmplPer();
                foreach ($_planillas as $key => $value) {
                    if ($dni == $value->getCodiEmplPer()) {
                        $dni = $value->getCodiEmplPer();
                        $planilla['codiEmplPer'] = $dni;
                        $planilla['descripcion'] = $value->getDescripcion();
                        $planilla[$value->getCodiConcTco()] = $value->getValoCalcPhi();
                        continue;
                    }
                    $array['payrolls'][$co] = $planilla;
                    $dni = $value->getCodiEmplPer();
                    $planilla['codiEmplPer'] = $dni;
                    $planilla['descripcion'] = $value->getDescripcion();
                    $planilla[$value->getCodiConcTco()] = $value->getValoCalcPhi();
                    $co++;
                }
                $array['payrolls'][$co] = $planilla;
            } else {
                $array = array('payrolls' => array_map(
                            create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
            }

            $_form = $this->createFormBuilder($array)
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
            $_form->handleRequest($request);
            if ($_form->isValid()) {
                /*                 * **GUARDAR** */
                $em = $this->getDoctrine()->getManager();
                $data = $_form->getData()['payrolls'];
                $q = $em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas m where m.folio = ' . $object->getCodiFolio());
                $q->execute();
                foreach ($data as $key => $planilla) {
                    $dni = $planilla['codiEmplPer'];
                    $descripcion = $planilla['descripcion'];
                    unset($planilla['codiEmplPer']);
                    unset($planilla['descripcion']);
                    foreach ($planilla as $key => $valor) {
                        //echo $object->getConcepto($key);
                        $planillah = new \Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas();
                        $planillah->setCodiConcTco($key);
                        $planillah->setTipoPlanTpl($object->getTipoPlanTpl()->getTipoPlanTpl());
                        $planillah->setSubtPlanTpl($object->getSubtPlanStp());
                        $planillah->setNumePeriTpe(01);
                        $planillah->setDescripcion($descripcion);
                        $planillah->setValoCalcPhi($valor);
                        $planillah->setCodiEmplPer($dni);
                        $planillah->setAnoPeriTpe($object->getTomo()->getAnoTomo());
                        $planillah->setFolio($object->getCodiFolio());
                        $em->persist($planillah);
                    }
                    $em->flush();
                    $em->clear();
                }
                $this->get('session')->getFlashBag()->add(
            'planilla',
            'Registro grabado satisfactoriamente'
            );
                $nextAction = '_planilla_add';                
                return $this->redirect($this->generateUrl($nextAction));
                $object = null;
            }
            $form = $_form->createView();
        }
        return array(
            'form' => $form,
            'sform' => $sform->createView(),
            'folio' => $object
        );
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
//        $form = $this->createForm('search_tomos', null, array(
//            'method' => 'GET',
//        ));
//        $form->handleRequest($request);
//        $criteria = $form->getData() ? $form->getData() : array();
//        foreach ($criteria as $key => $value) {
//            if (!$value) {
//                unset($criteria[$key]);
//            }
//        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tplanilla');
//        $query = $em->findBy($criteria);
        $query = $em->findBy(array(), array(
            'descTipoTpl' => 'ASC'
        ));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 15
        );
        return array(
            'pagination' => $pagination,
            //'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/tplanilla/add/", name="_planilla_tplanilla_add")
     * @Template("")
     */
    public function addTplanillaAction(Request $request) {
        $object = new Tplanilla();
        $form = $this->createForm('tplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'tplanilla',
            'Registro grabado satisfactoriamente'
            );
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
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Tplanilla');
        $object = $em->find($pk);
        $form = $this->createForm('tplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'tplanilla',
            'Registro modificado satisfactoriamente'
            );
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
//        $form = $this->createForm('search_tomos', null, array(
//            'method' => 'GET',
//        ));
//        $form->handleRequest($request);
//        $criteria = $form->getData() ? $form->getData() : array();
//        foreach ($criteria as $key => $value) {
//            if (!$value) {
//                unset($criteria[$key]);
//            }
//        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Subtplanilla');
//        $query = $em->findBy($criteria);
        $query = $em->findBy(array(), array(
            'descSubtStp' => 'ASC'
        ));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 15
        );
        return array(
            'pagination' => $pagination,
            //'form' => $form->createView()
        );
    }
    
    /**
     * @Route("/subtplanilla/add/", name="_planilla_subtplanilla_add")
     * @Template("")
     */
    public function addSubtplanillaAction(Request $request) {
        $object = new Subtplanilla();
        $form = $this->createForm('subtplanilla', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'subtplanilla',
            'Registro grabado satisfactoriamente'
            );
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
    public function editSubtplanillaAction(Request $request,$planilla, $pk) {
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'subtplanilla',
            'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_planilla_subtplanilla_add' : '_planilla_subtplanilla_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }
}
