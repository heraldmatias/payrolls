<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\Conceptos;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;

/**
 * Description of InventarioController
 *
 * @author holivares
 */
class ConceptoController extends Controller {

    /**
     * @Route("/", name="_concepto_list")
     * @Template("")
     */
    public function listAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('concepto', 'query')) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('search_concepto', null);
        $form->handleRequest($request);
        $criteria = $form->getData() ? $form->getData() : array();
        foreach ($criteria as $key => $value) {
            if (!$value) {
                unset($criteria[$key]);
            }
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Conceptos');
        $query = $em->findUsingLike($criteria, 'order by t.fecCreac DESC');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 20/* limit per page */
        );
        return array(
            'pagination' => $pagination,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/add", name="_concepto_add")
     * @Template("")
     */
    public function addAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('concepto', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new Conceptos();
        $form = $this->createForm('concepto', $object);
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
                        'concepto', 'Registro grabado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'concepto', 'Ocurrio un error al grabar el registro'
                );
            }
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
     */
    public function editAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('concepto', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:Conceptos');
        $object = $em->find($pk);
        if (!$object) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm('concepto', $object);
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
                        'concepto', 'Registro modificado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'concepto', 'Ocurrio un error al actualizar'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_concepto_add' : '_concepto_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

}