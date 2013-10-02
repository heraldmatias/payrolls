<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Conceptos;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Description of InventarioController
 *
 * @author holivares
 */
class ConceptoController extends Controller {

    /**
     * @Route("/", name="_concepto_list")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c FROM IneiPayrollBundle:Conceptos c ORDER BY c.codiConcTco ASC'
        );
        $list = $query->getResult();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $list, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        return array(
            'pagination' => $pagination
        );
    }

    /**
     * @Route("/add", name="_concepto_add")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function addAction(Request $request) {
        $object = new Conceptos();
        $form = $this->createForm('concepto', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'concepto',
            'Registro grabado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_concepto_add' : '_concepto_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{pk}", name="_concepto_edit")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function editAction(Request $request, $pk) {
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Conceptos');
        $object = $em->find($pk);
        $form = $this->createForm('concepto', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'concepto',
            'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_concepto_add' : '_concepto_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

}