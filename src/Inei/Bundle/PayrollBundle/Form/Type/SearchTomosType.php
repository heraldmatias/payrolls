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
use Inei\Bundle\PayrollBundle\Form\DataTransformer\ValidateConceptTransformer;

class SearchTomosType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('anoTomo', 'choice', array(
                    'attr' => array(
                        'class' => 'folio',
                        'style' => 'width:100%;'
                     ),
                    'required' => false,
                    'choices' => $this->getAnos(),
                    'label' => 'Año del Tomo'
                ))
                ->add('periodoTomo', 'choice', array(
                    'attr' => array(
                        'class' => 'periodo',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'choices' => $options['periodos'],
                    'empty_value' => '---SELECCIONE---',
                    'label' => 'Periodo del Tomo'
                ))
                ->add('foliosTomo', 'number', array(
                    'attr' => array(
                        'class' => 'registros',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'label' => 'Folios'
                ))
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }

    private function getAnos(){
        $codes = array();
        foreach (range(1961, 2002) as $number) {
            $codes[$number] = $number;
        }
        return $codes;
    }
    
    public function getName() {
        return 'search_tomos';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {        
        
        $resolver->setRequired(array(
            'periodos',
        ));
        
    }

}