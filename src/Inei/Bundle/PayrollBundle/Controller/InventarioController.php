<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Tomos;
use Symfony\Component\HttpFoundation\Request;

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
        $form = $this->createForm('tomo', $object);
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
        $form = $this->createForm('tomo', $object);
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

}