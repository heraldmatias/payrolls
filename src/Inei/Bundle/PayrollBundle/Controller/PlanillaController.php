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
use Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas;

/**
 * Description of InventarioController
 *
 * @author holivares
 */
class PlanillaController extends Controller {

    /**
     * @Route("/autosave/", name="_planilla_autosave")
     * @Template("")
     */
    public function autoSaveAction(Request $request) {

        $_data = $request->request->get('form');
        //echo json_encode($_data);
        $tomo = $request->request->get('tomo');
        $folio = $request->request->get('folio');
        $filename = 'planilla' . $tomo . '_' . $folio . '.json';
        $date = new \DateTime();
        $data = json_encode($_data);
        //echo $request;
        $fp = __DIR__ . '/../../../../../web/' . $filename;

        file_put_contents($fp, $data);

        $response = new Response('El documento se guardo por ultima vez '.$date->format('Y-m-d H:i:s'));
        //$response->headers->set('content-type', 'application/json');
        return $response;
    }

    public function loadAutoSave($filename) {
        //$filename = 'planilla' . $tomo . '_' . $folio . '.json';
        //$fp = __DIR__ . '/../../../../../web/' . $filename;
        $result = null;
        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            $result = json_decode($data, true);
        }
        return $result;
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
        $filename = null;
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

        if ($object && $object->getRegistrosFolio()) {
            $filename = __DIR__ . '/../../../../../web/planilla' . $tomo . '_' . $folio . '.json';
            $array = $this->loadAutoSave($filename);
            if (!$array) {
                $_planillas = $object->getPlanillas($this->getDoctrine()->getManager());
                $planilla = array();
                //if(null != $object->getRegistrosFolio())
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
//                        $key = null !== $value->getFlag() ?
//                                $value->getCodiConcTco() . '_' . $value->getFlag() :
//                                $value->getCodiConcTco();
                            $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                            //$planilla->setCodiConcTco(false !== $pos? substr($key, 0, count($key)-$pos) :$key);
                            //echo $value->getValoCalcPhi().'<br>';
                            $planilla[$key] = $value->getValoCalcPhi();
                            continue;
                        }
//                    $key = null !== $value->getFlag() ?
//                                $value->getCodiConcTco() . '_' . $value->getFlag() :
//                                $value->getCodiConcTco();
                        $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                        //echo $value->getValoCalcPhi().'<br>';
                        $array['payrolls'][$co] = $planilla;
                        $dni = $value->getCodiEmplPer();
                        $planilla['codiEmplPer'] = strtoupper($dni);
                        $planilla['descripcion'] = $value->getDescripcion();
                        $planilla[$key] = $value->getValoCalcPhi();
                        $co++;
                        if ($co > $object->getRegistrosFolio() - 1)
                            break;
                    }
                    if ($co <= $object->getRegistrosFolio() - 1)
                        $array['payrolls'][$co] = $planilla;
                } else {
                    $array = array('payrolls' => array_map(
                                create_function('$item', 'return array();'), range(1, $object->getRegistrosFolio())));
                }
            }

            $_form = $this->createFormBuilder($array, array(
                        'attr' => array(
                            'id' => 'form_planilla'
                        )
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
            $_form->handleRequest($request);
            if ($_form->isValid()) {
//                if(!$this->get('usuario_service')->hasPermission('planilla','add%edit')){
//                    throw $this->createNotFoundException();
//                }
                /**                 * *GUARDAR** */
                try {
                    $em = $this->getDoctrine()->getManager();
                    $_data = $_form->getData();
                    $data = $_data['payrolls'];
                    $q = $em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas m where m.folio = ' . $object->getCodiFolio());
                    $q->execute();
                    foreach ($data as $key => $planilla) {
                        $dni = $planilla['codiEmplPer'];
                        $descripcion = $planilla['descripcion'];
                        unset($planilla['codiEmplPer']);
                        unset($planilla['descripcion']);
                        foreach ($planilla as $key => $valor) {
                            $planillah = new PlanillaHistoricas();
                            //echo $key.'<br>';
                            $pos = strpos($key, '_');
                            $planillah->setCodiConcTco(false !== $pos ? substr($key, 0, $pos) : $key);
                            $planillah->setFlag(false !== $pos ? substr($key, $pos + 1) : NULL);
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
                    }
                    $em->flush();
                    $em->clear();
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Registro grabado satisfactoriamente'
                    );
                    /**********borra el autoguardado*********/
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                } catch (Doctrine\DBAL\DBALException $e) {
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Ocurrio un erro al grabar la planilla'
                    );
                }
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
     * @Route("/add/old", name="_planilla_add_old")
     * @Template("")
     */
    public function addOldAction(Request $request) {
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

        if ($object && $object->getRegistrosFolio()) {

            $_planillas = $object->getPlanillas($this->getDoctrine()->getManager());
            $planilla = array();
            //if(null != $object->getRegistrosFolio())
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
//                        $key = null !== $value->getFlag() ?
//                                $value->getCodiConcTco() . '_' . $value->getFlag() :
//                                $value->getCodiConcTco();
                        $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                        //$planilla->setCodiConcTco(false !== $pos? substr($key, 0, count($key)-$pos) :$key);
                        //echo $value->getValoCalcPhi().'<br>';
                        $planilla[$key] = $value->getValoCalcPhi();
                        continue;
                    }
//                    $key = null !== $value->getFlag() ?
//                                $value->getCodiConcTco() . '_' . $value->getFlag() :
//                                $value->getCodiConcTco();
                    $key = $value->getCodiConcTco() . '_' . $value->getFlag();
                    //echo $value->getValoCalcPhi().'<br>';
                    $array['payrolls'][$co] = $planilla;
                    $dni = $value->getCodiEmplPer();
                    $planilla['codiEmplPer'] = strtoupper($dni);
                    $planilla['descripcion'] = $value->getDescripcion();
                    $planilla[$key] = $value->getValoCalcPhi();
                    $co++;
                    if ($co > $object->getRegistrosFolio() - 1)
                        break;
                }
                if ($co <= $object->getRegistrosFolio() - 1)
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
//                if(!$this->get('usuario_service')->hasPermission('planilla','add%edit')){
//                    throw $this->createNotFoundException();
//                }
                /**                 * *GUARDAR** */
                try {
                    $em = $this->getDoctrine()->getManager();
                    $_data = $_form->getData();
                    $data = $_data['payrolls'];
                    $q = $em->createQuery('delete from IneiPayrollBundle:PlanillaHistoricas m where m.folio = ' . $object->getCodiFolio());
                    $q->execute();
                    foreach ($data as $key => $planilla) {
                        $dni = $planilla['codiEmplPer'];
                        $descripcion = $planilla['descripcion'];
                        unset($planilla['codiEmplPer']);
                        unset($planilla['descripcion']);
                        foreach ($planilla as $key => $valor) {
                            $planillah = new PlanillaHistoricas();
                            //echo $key.'<br>';
                            $pos = strpos($key, '_');
                            $planillah->setCodiConcTco(false !== $pos ? substr($key, 0, $pos) : $key);
                            $planillah->setFlag(false !== $pos ? substr($key, $pos + 1) : NULL);
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
                    }
                    $em->flush();
                    $em->clear();
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Registro grabado satisfactoriamente'
                    );
                } catch (Doctrine\DBAL\DBALException $e) {
                    $this->get('session')->getFlashBag()->add(
                            'planilla', 'Ocurrio un erro al grabar la planilla'
                    );
                }
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
