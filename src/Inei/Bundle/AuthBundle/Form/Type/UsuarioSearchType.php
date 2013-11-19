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

class UsuarioSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombres', 'text', array(
                    'required' => false,
                    'label' => 'Nombres',
                    'attr' => array(                        
                        'style' => 'width:95%;'
                    )
                ))
                ->add('username', 'text', array(
                    'attr' => array(
                        'style' => 'width:95%;'
                        ),
                    'required' => false,
                    'label' => 'Usuario'
                ))
                ->add('search', 'submit', array(
                    'label' => 'Buscar',
                    'attr' => array('class' => 'btn btn-primary'),));
    }
    
    public function getName() {
        return 'usuario_search';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'method' => 'GET',
            'csrf_protection' => false
        ));
    }

}