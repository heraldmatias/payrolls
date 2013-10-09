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

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaestroPersonalType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('apePatPer', null, array(
                    'label' => 'Apellido Paterno'
                ))
                ->add('apeMatPer', null, array(
                    'label' => 'Apellido Materno'
                ))
                ->add('nomEmpPer', null, array(
                    'label' => 'Nombre Completo'
                ))
                ->add('nombCortPer', null, array(
                    'label' => 'Nombre Corto',
                    'attr' => array(
                        'style' => 'width:100%;',
                        'rows' => '5'
                    )
                ))
                ->add('dirEmpPer', null, array(
                    'label' => 'Dirección',
                    'attr' => array(
                        'style' => 'width:100%;',
                        'rows' => '5'
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'personal';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\MaestroPersonal',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
            'attr' => array(
                'style' => 'margin:50px;'
            ),
        ));
    }

}
