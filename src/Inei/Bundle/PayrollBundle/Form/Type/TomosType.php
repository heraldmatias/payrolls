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

class TomosType extends AbstractType {

    private function generateCodes(){
        $codes = array();
        foreach (range(1, 419) as $number) {
            $codes[$number] = 'TOMO - '.$number;
        }
        return $codes;
    }
    
    private function getAnos(){
        $codes = array();
        foreach (range(1961, 2002) as $number) {
            $codes[$number] = $number;
        }
        return $codes;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codiTomo', 'choice', array(
                    'label' => 'Tomo',
                    'choices' => $this->generateCodes(),
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('anoTomo', null, array(
                    'label' => 'Año',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('periodoTomo', null, array(
                    'label' => 'Periodo',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('foliosTomo', null, array(
                    'label' => 'Folios',
                    'attr' => array(
                        'style' => 'width: 100%'
                    )
                ))
                ->add('descTomo', null, array(
                    'label' => 'Descripción',
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
        return 'tomos';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Tomos',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}
