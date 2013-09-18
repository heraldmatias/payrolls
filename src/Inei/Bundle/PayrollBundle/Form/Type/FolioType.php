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

class FolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entityManager = $options['em'];
        $transformer = new ValidateConceptTransformer($entityManager);
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
                ->add($builder->create('conceptos', 'collection', array(
                    'type'         => new ConceptoFolioType(),
                    'allow_add'    => true,
                    'allow_delete'    => true,
                    'by_reference' => false,
                    'prototype_name' => '__conceptform__'))->addModelTransformer($transformer)
                );
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
        
        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

}
