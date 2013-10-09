<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\MaestroPersonal;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Description of InventarioController
 *
 * @author holivares
 */
class MaestroPersonalController extends Controller {

    /**
     * @Route("/", name="_personal_list")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function listAction(Request $request) {
        $form = $this->createForm('search_personal', null);
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:MaestroPersonal');
        $query = $em->findUsingLike($criteria, 'order by t.apePatPer ASC');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 10/* limit per page */
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/add", name="_personal_add")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function addAction(Request $request) {
        $object = new MaestroPersonal();
        $form = $this->createForm('personal', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'personal',
            'Registro grabado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_personal_add' : '_personal_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{pk}", name="_personal_edit")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_CONCEPTO")
     */
    public function editAction(Request $request, $pk) {
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:MaestroPersonal');
        $object = $em->find($pk);
        $form = $this->createForm('personal', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'personal',
            'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_personal_add' : '_personal_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

}