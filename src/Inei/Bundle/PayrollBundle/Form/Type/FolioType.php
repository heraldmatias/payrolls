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

class FolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('folio', null, array(
                    'attr' => array('class' => 'folio')
                ))
                ->add('periodoFolio', null, array(
                    'attr' => array('class' => 'periodo')
                ))
                ->add('registrosFolio', null, array(
                    'attr' => array('class' => 'registros')
                ))
                ->add('tipoPlanTpl', null, array(
                    'attr' => array('class' => 'planilla')
                ))
                ->add('subtPlanStp', null, array())
                ->add('conceptos', 'collection', array(
                    'type'         => new ConceptoFolioType(),
                    'allow_add'    => true,
                    'by_reference' => false,
                    'prototype_name' => '__conceptform__'
                ));
    }

    public function getName() {
        return 'folio';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Folios',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}
