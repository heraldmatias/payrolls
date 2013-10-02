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
class RoleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, array(
                    'label' => 'Nombre Corto'
                ))
                ->add('role', null, array(
                    'label' => 'Rol',
                    //'data' => 'ROLE_'
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'role';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
           'data_class' => 'Inei\Bundle\AuthBundle\Entity\Role',
           'csrf_protection' => true,
           'csrf_field_name' => '_token',
        ));
    }

}

