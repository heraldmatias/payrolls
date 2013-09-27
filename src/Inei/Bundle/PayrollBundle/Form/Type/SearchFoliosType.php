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

class SearchFoliosType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tomo', 'choice', array(
                    'attr' => array(
                        'class' => 'tomo',
                        'style' => 'width:100%;'
                     ),
                    'required' => false,
                    'choices' => array_combine(range(1, 419),range(1, 419)),
                    'empty_value' => '---SELECCIONE---'
                ))
                ->add('folio', 'choice', array(
                    'attr' => array(
                        'class' => 'folio',
                        'style' => 'width:100%;'
                     ),
                    'required' => false,
                    'empty_value' => '---SELECCIONE---',
                    'choices' => array_combine(range(1, 500),range(1, 500)),
                    'label' => 'Numero de Folio'
                ))
                ->add('periodoFolio', 'choice', array(
                    'attr' => array(
                        'class' => 'periodo',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'choices' => $options['periodos'],
                    'empty_value' => '---SELECCIONE---'
                ))
                ->add('registrosFolio', 'number', array(
                    'attr' => array(
                        'class' => 'registros',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'label' => 'Registros Por Folio'
                ))
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }

    public function getName() {
        return 'search_folios';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {        
        
        $resolver->setRequired(array(
            'periodos',
        ));
        
    }

}