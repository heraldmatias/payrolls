<?php

namespace Inei\Bundle\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Inei\Bundle\AuthBundle\Entity\Usuarios;
use Inei\Bundle\AuthBundle\Entity\Role;

/**
 * Description of SecurityCrudController
 *
 * @author holivares
 */
class SecurityCrudController extends Controller {

    /**
     * @Route("/user", name="_admin_user_list")
     * @Template("")     
     */
    public function listUserAction(Request $request) {
        if(!$this->get('usuario_service')->hasPermission('usuario','query')){
            throw $this->createNotFoundException();
        }
        $criteria = array();
//        $criteria = $form->getData() ? $form->getData() : array();
//        foreach ($criteria as $key => $value) {
//            if (!$value) {
//                unset($criteria[$key]);
//            }
//        }
        $em = $this->getDoctrine()
                ->getRepository('IneiAuthBundle:Usuarios');
        $query = $em->findBy($criteria);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );


        return array(
            'pagination' => $pagination,
                //'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/add", name="_admin_user_add")
     * @Template("")
     */
    public function addUserAction(Request $request) {
         if(!$this->get('usuario_service')->hasPermission('usuario','add')){
            throw $this->createNotFoundException();
        }
        $usuario = new Usuarios();
        $form = $this->createForm('usuario', $usuario);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            $factory = $this->get('security.encoder_factory');
//            $encoder = $factory->getEncoder($usuario);
//            $password = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
//            $usuario->setPassword($password);
            $this->get('usuario_service')->encodePassword($usuario);
            $em->persist($usuario);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                    'usuario', 'Registro grabado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_user_add' : '_admin_user_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/{pk}", name="_admin_user_edit")
     * @Template("")
     */
    public function editUserAction(Request $request, $pk) {
        if(!$this->get('usuario_service')->hasPermission('usuario','edit')){
            throw $this->createNotFoundException();
        }
        $usuario = $this->getDoctrine()
                        ->getRepository('IneiAuthBundle:Usuarios')->find($pk);
        $form = $this->createForm('usuario', $usuario);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->get('usuario_service')->encodePassword($usuario);
            $em->persist($usuario);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                    'usuario', 'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_user_add' : '_admin_user_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /*     * **********************************ROLES************************************* */

    /**
     * @Route("/role", name="_admin_role_list")
     * @Template("")
     */
    public function listRoleAction(Request $request) {
        if(!$this->get('usuario_service')->hasPermission('rol','query')){
            throw $this->createNotFoundException();
        }
        $criteria = array();
//        $criteria = $form->getData() ? $form->getData() : array();
//        foreach ($criteria as $key => $value) {
//            if (!$value) {
//                unset($criteria[$key]);
//            }
//        }
        $em = $this->getDoctrine()
                ->getRepository('IneiAuthBundle:Role');
        $query = $em->findBy($criteria);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );


        return array(
            'pagination' => $pagination,
                //'form' => $form->createView()
        );
    }

    /**
     * @Route("/role/add", name="_admin_role_add")
     * @Template("")     
     */
    public function addRoleAction(Request $request) {
         if(!$this->get('usuario_service')->hasPermission('rol','add')){
            throw $this->createNotFoundException();
        }
        $object = new Role();
        $form = $this->createForm('role', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $conn = $this->get('database_connection');
            try {
                $conn->beginTransaction();
                $em = $this->getDoctrine()->getManager();
                $permissions = $object->getPermissions()->toArray();
                $object->getPermissions()->clear();
                $em->persist($object);
                $em->flush();
                foreach ($permissions as $item) {
                    $item->setRole($object);
                    $em->persist($item);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'role', 'Registro grabado satisfactoriamente'
                );
                $conn->commit();
            } catch (DBALException $e) {
                $conn->rollback();
            }
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_role_add' : '_admin_role_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/role/{pk}", name="_admin_role_edit")
     * @Template("")
     */
    public function editRoleAction(Request $request, $pk) {
        if(!$this->get('usuario_service')->hasPermission('rol','edit')){
            throw $this->createNotFoundException();
        }
        $object = $this->getDoctrine()
                        ->getRepository('IneiAuthBundle:Role')->findOneCustomBy($pk);
        $form = $this->createForm('role', $object);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            if ($object->getRmpermissions()) {
                foreach ($object->getRmpermissions() as $value) {
                    $em->remove($value);
                }
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add(
                    'role', 'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_role_add' : '_admin_role_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }

}