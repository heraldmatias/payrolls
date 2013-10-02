<?php

namespace Inei\Bundle\AuthBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Inei\Bundle\AuthBundle\Entity\Usuarios;
use Inei\Bundle\AuthBundle\Entity\Role;
//use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Description of SecurityCrudController
 *
 * @author holivares
 */
class SecurityCrudController extends Controller {
    
    /**
     * @Route("/user", name="_admin_user_list")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function listUserAction(Request $request){
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
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function addUserAction(Request $request){
        $usuario = new Usuarios();
        $form = $this->createForm('usuario', $usuario);
        $form->handleRequest($request);
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
//            $factory = $this->get('security.encoder_factory');
//            $encoder = $factory->getEncoder($usuario);
//            $password = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
//            $usuario->setPassword($password);
            $this->get('usuario_service')->encodePassword($usuario);
            $em->persist($usuario);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'usuario',
            'Registro grabado satisfactoriamente'
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
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function editUserAction(Request $request, $pk){
        $usuario = $this->getDoctrine()
                ->getRepository('IneiAuthBundle:Usuarios')->find($pk);
        $form = $this->createForm('usuario', $usuario);
        $form->handleRequest($request);
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $this->get('usuario_service')->encodePassword($usuario);
            $em->persist($usuario);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'usuario',
            'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_user_add' : '_admin_user_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }
    
    /************************************ROLES**************************************/
    /**
     * @Route("/role", name="_admin_role_list")
     * @Template("")
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function listRoleAction(Request $request){
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
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function addRoleAction(Request $request){
        $object = new Role();
        $form = $this->createForm('role', $object);
        $form->handleRequest($request);
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'role',
            'Registro grabado satisfactoriamente'
            );
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
     * @Secure(roles="ROLE_ADMINISTRADOR, ROLE_SEGURIDAD")
     */
    public function editRoleAction(Request $request, $pk){
        $object = $this->getDoctrine()
                ->getRepository('IneiAuthBundle:Role')->find($pk);
        $form = $this->createForm('role', $object);
        $form->handleRequest($request);
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
            'role',
            'Registro modificado satisfactoriamente'
            );
            $nextAction = $form->get('saveAndAdd')->isClicked() ? '_admin_role_add' : '_admin_role_list';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return array(
            'form' => $form->createView()
        );
    }
}