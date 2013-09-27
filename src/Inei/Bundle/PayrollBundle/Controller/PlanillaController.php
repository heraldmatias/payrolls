<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Tomos;
use Inei\Bundle\PayrollBundle\Entity\Folios;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Inei\Bundle\PayrollBundle\Form\Type\PlanillaType;

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
            $object = $em->findOneBy(array('folio' => $pk)); 
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

}
