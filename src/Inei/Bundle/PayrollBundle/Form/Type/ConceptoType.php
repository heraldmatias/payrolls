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

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codiConcTco', null, array(
                    'label' => 'Codigo',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('descConcTco', null, array(
                    'label' => 'Descripcion',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('descCortTco', null, array(
                    'label' => 'Descripcion Corta',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('tipoConcTco', 'choice', array(
                    'label' => 'Tipo Concepto',
                    'choices' => array(
                        0 => 'Tiempo', 1 => 'Ingresos', 2 => 'Egresos', 3 => 'Aportaciones', 4 => 'Otros'
                    ),
                    'empty_value' => '---Ninguno---',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 95%'
                    )
                    
                ))
                ->add('tipoCalcTco', 'choice', array(
                    'label' => 'Tipo Calculo',
                    'choices' => array(
                        0 => 'Otros', 1 => 'Fijo', 2  => 'Formula'
                    ),
                    'empty_value' => '---Ninguno---',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 95%'
                    )
                ))
                ->add('secuCalcTco', null, array(
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('flagAsocTco', null, array(
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('flagRecuTco', null, array(
                    'label' => 'Flag',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('rntaQntaTco', null, array(
                    'label' => 'Renta Quinta',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('ctsCtsTco', null, array(
                    'label' => 'Seleccion de CTS',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('codiConcOnc', null, array(
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('codiEntiEnt', null, array(
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('cntaDebeTco', null, array(
                    'label' => 'Cuenta Debe',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('cntaHabeTco', null, array(
                    'label' => 'Cuenta Haber',
                    'attr' => array(
                        'style' => 'width: 90%'
                    )
                ))
                ->add('clasConcTco', 'choice', array(
                    'choices' => array(
                        1 => 'Fijo', 2 => 'Variable'
                    ),
                    'empty_value' => '---Ninguno---',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 95%'
                    )
                ))
                ->add('flagPagoTco', 'choice', array(
                    'choices' => array(
                        1 => 'INEI', 2 => 'MEF'
                    ),
                    'label' => 'Pago',
                    'empty_value' => '---Ninguno---',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 95%'
                    )
                ))
                ->add('sedeConcTco', 'choice', array(
                    'choices' => array(
                        0 => 'Lima', 1 => 'ODEIS'
                    ),
                    'label' => 'Sede',
                    'empty_value' => '---Ninguno---',
                    'required' => false,
                    'attr' => array(
                        'style' => 'width: 95%'
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
        return 'concepto';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Conceptos',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'method' => 'post',
            'intention' => 'task_item',
        ));
    }

}
