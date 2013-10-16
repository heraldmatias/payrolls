<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TomoForm
 *
 * @author holivares
 */
// src/Inei/Bundle/PayrollBundle/Form/Type/TomoType.php

namespace Inei\Bundle\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('module', null, array(
                    'attr' => array('class' => 'module')
                ))
                ->add('type', 'choice', array(
                    'attr' => array('class' => 'row-fluid'),
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => \Inei\Bundle\AuthBundle\Entity\Permission::$PERMISSION_TYPE
                ));
    }

    public function getName() {
        return 'permission';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\AuthBundle\Entity\Permission',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention' => 'task_item'            
        ));
    }

}
