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

class SearchMaestroPersonalType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('apePatPer', 'text', array(
                    'attr' => array(
                        'class' => 'paterno',
                        'style' => 'width:100%;'
                     ),
                    'required' => false,                    
                    'label' => 'Apellido paterno'
                ))
                ->add('apeMatPer', 'text', array(
                    'attr' => array(
                        'class' => 'materno',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'label' => 'Apellido Materno'
                ))
                ->add('nomEmpPer', 'text', array(
                    'attr' => array(
                        'class' => 'nombres',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'label' => 'Nombres'
                ))
                ->add('librElecPer', null, array(
                    'required' => false,
                    'max_length' => 8,
                    'attr' => array(
                        'style' => 'width: 100%',
                    )
                ))
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }
    
    public function getName() {
        return 'search_personal';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {        
        
        $resolver->setDefaults(array(
            'method' => 'GET',
            'csrf_protection' => false
        ));
        
    }

}