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

class SubtplanillaType extends AbstractType {
        
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipoPlanTpl', null, array(
                    'label' => 'Tipo Planilla',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('subtPlanStp', null, array(
                    'label' => 'Codigo Sub Tipo',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('descSubtStp', null, array(
                    'label' => 'Descripción',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('tituSubtStp', null, array(
                    'label' => 'Descripción en Planilla',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('observ', null, array(
                    'label' => 'Observación'
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'subtplanilla';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Subtplanilla',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}
