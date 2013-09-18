<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Tomos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of InventarioController
 *
 * @author holivares
 */
class InventarioController extends Controller {

    /**
     * @Route("/", name="_inventario_list")
     * @Template("")
     */
    public function tomosAction() {
        $em = $this->getDoctrine()
            ->getRepository('IneiPayrollBundle:Tomos');
        $query = $em->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route("/add", name="_inventario_add")
     * @Template("")
     */
    public function addTomosAction(Request $request) {
        $object = new Tomos();
        $form = $this->createForm('tomo', $object, array('em' => $this->getDoctrine()->getManager()));
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_add' : '_inventario_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{pk}", name="_inventario_edit")
     * @Template("")
     */
    public function editTomosAction(Request $request, $pk) {
        $em = $this->getDoctrine()
            ->getRepository('IneiPayrollBundle:Tomos');
        $object = $em->find($pk);
        //$object->getConceptos();
        $form = $this->createForm('tomo', $object, array('em' => $this->getDoctrine()->getManager()));
        //if($request->getMethod()==='POST'){
//            $em = $this->getDoctrine()->getManager();
//            foreach ($object->getFolios() as $key => $value) {
//                $value->getConceptos()->clear();
//            }
//            $em->persist($object);
//                $em->flush();
        //}
        $form->handleRequest($request);
        /*BUSCAR ACTUALIZAR LA RELACION SIN QUE INSERTE*/
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_inventario_add' : '_inventario_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView(),
            //'conceptos' => $object->getFolios()[2]->getConceptos()
        );
    }

    /**
     * @Route("/save/test", name="_inventario_test")
     * @Template("")
     */
    public function testAction(){
        $emf = $this->getDoctrine()->getRepository('IneiPayrollBundle:Folios');
        $emc = $this->getDoctrine()->getRepository('IneiPayrollBundle:Conceptos');
        $em = $this->getDoctrine()->getManager();
        $folio = $emf->findOneBy(array('codiFolio' => 35));
        $concept1 = $emc->findOneBy(array('codiConcTco' => '00103'));
        $concept2 = $emc->findOneBy(array('codiConcTco' => '00102'));
        $concept3 = $emc->findOneBy(array('codiConcTco' => '00101'));
        if(!$folio->getConceptos()->contains($concept1)){
            $folio->addConcepto($concept1);
        }
        if(!$folio->getConceptos()->contains($concept2)){
            $folio->addConcepto($concept2);
        }
        if(!$folio->getConceptos()->contains($concept3)){
            $folio->getConceptos()->set(2, $concept3);
        }
        $em->persist($folio);
        $em->flush();
        return new Response(
            'TODOD BIEN'
        );
    }
}