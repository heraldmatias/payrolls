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

class TomoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('anoTomo', null, array(
                    'label' => 'Año'
                ))
                ->add('periodoTomo', null, array(
                    'label' => 'Periodo'
                ))
                ->add('foliosTomo', null, array(
                    'label' => 'Folios'
                ))
                ->add('descTomo', null, array(
                    'label' => 'Descripción',
                    'attr' => array(
                        'style' => 'width:100%;',
                        'rows' => '5'
                    )
                ))
                ->add('folios', 'collection', array(
                    'type' => new FolioType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype_name' => '__folioform__',
                    'options' => array('em' => $options['em'],)
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'tomo';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Tomos',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

}
