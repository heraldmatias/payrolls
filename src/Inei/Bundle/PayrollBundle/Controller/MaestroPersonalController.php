<?php

namespace Inei\Bundle\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inei\Bundle\PayrollBundle\Entity\MaestroPersonal;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\DBALException;

/**
 * Description of InventarioController
 *
 * @author holivares
 */
class MaestroPersonalController extends Controller {

    /**
     * @Route("/", name="_personal_list")
     * @Template("")     
     */
    public function listAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('personal', 'query')) {
            throw $this->createNotFoundException();
        }
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
        $query = $em->findUsingLike($criteria, 'order by t.apePatPer ASC'); //$em->findAll();//
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
     */
    public function addAction(Request $request) {
        if (!$this->get('usuario_service')->hasPermission('personal', 'add')) {
            throw $this->createNotFoundException();
        }
        $object = new MaestroPersonal();
        $form = $this->createForm('personal', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'personal', 'Registro grabado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'personal', 'Ocurrio un error al grabar el registro'
                );
            }
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
     */
    public function editAction(Request $request, $pk) {
        if (!$this->get('usuario_service')->hasPermission('personal', 'edit')) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:MaestroPersonal');
        $object = $em->find($pk);
        $form = $this->createForm('personal', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($object);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'personal', 'Registro modificado satisfactoriamente'
                );
            } catch (DBALException $e) {
                $this->get('session')->getFlashBag()->add(
                        'personal', 'Ocurrio un error al modificar el registro'
                );
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_personal_add' : '_personal_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/ajax/typehead", name="_personal_ajax")
     * 
     */
    public function ajaxTypeheadFunction(Request $request) {
        $nombres = $request->query->get('query');
        $em = $this->getDoctrine()
                ->getRepository('IneiPayrollBundle:MaestroPersonal');
//        $data = array_map(create_function('$person', 'return array_values($person)[0];'), $em->findByFullname($nombres));
        $data = $em->findByFullname($nombres);
        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($data));
        $response->headers->set('content-type', 'application/json');
        return $response;
    }

}