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

class SearchConceptoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codiConcTco', 'text', array(
                    'required' => false,
                    'label' => 'Codigo',
                    'attr' => array(
                        'class' => 'registros',
                        'style' => 'width:90%;'
                    )
                ))
                ->add('descConcTco', 'text', array(
                    'attr' => array(
                        'class' => 'registros',
                        'style' => 'width:90%;'
                        ),
                    'required' => false,
                    'label' => 'Descripción'
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
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }

    public function getName() {
        return 'search_concepto';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'method' => 'GET',
            'csrf_protection' => false
        ));
    }

}