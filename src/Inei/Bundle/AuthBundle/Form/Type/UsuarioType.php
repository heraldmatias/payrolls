<?php

namespace Inei\Bundle\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Description of UsuarioType
 *
 * @author holivares
 */
class UsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombres', null, array())
                ->add('username', null, array())
                ->add('password', 'password', array(
                    'trim' => true
                ))
                ->add('email', 'email', array(
                    'required' => false
                ))
                ->add('isActive', null, array(
                    'required' => false,
                    'label' => 'Activo'
                ))
                ->add('rolesCollection', 'entity', array(
                    'label' => 'Roles',
                    'multiple' => true,
                    'property' => 'role',
                    'class' => 'IneiAuthBundle:Role',
                    'attr' => array(
                        'size' => '10'
                    )
                    //'expanded' => true
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'usuario';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
           'data_class' => 'Inei\Bundle\AuthBundle\Entity\Usuarios',
           'csrf_protection' => true,
           'csrf_field_name' => '_token',
        ));
    }

}

