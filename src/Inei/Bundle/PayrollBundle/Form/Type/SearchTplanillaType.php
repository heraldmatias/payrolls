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

class SearchTplanillaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipoPlanTpl', 'text', array(
                    'label' => 'Codigo',
                    'required' => false
                ))
                ->add('descTipoTpl', 'text', array(
                    'attr' => array(
                        'class' => 'registros',
                        'style' => 'width:100%;'
                        ),
                    'required' => false,
                    'label' => 'Descripción'
                ))
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }
    
    public function getName() {
        return 'search_tplanilla';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'method' => 'GET',
            'csrf_protection' => false
        ));
    }

}