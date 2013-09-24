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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FoliosType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entityManager = $options['em'];
        $transformer = new ValidateConceptTransformer($entityManager);
        $builder->add('tomo', null, array(
                    'attr' => array('class' => 'tomo'),
                    'empty_value' => '---Seleccione---',
                    'label' => 'Tomo'
                ))
                ->add('periodoFolio', null, array(
                    'attr' => array('class' => 'periodo'),
                    'label' => 'Periodo'
                ))
                ->add('registrosFolio', null, array(
                    'attr' => array('class' => 'registros',),
                    'label' => 'Registros'
                ))
                ->add('tipoPlanTpl', null, array(
                    'attr' => array('class' => 'planilla'),
                    'label' => 'Planilla'
                ))
                ->add('subtPlanStp', null, array(
                    'label' => 'Sub Tipo Planilla'
                ))
                ->add($builder->create('conceptos', 'collection', array(
                            'type' => new ConceptoFoliosType(),
                            'allow_add' => true,
                            'allow_delete' => true,
                            'by_reference' => false,
                            'prototype_name' => '__conceptform__'))//->addModelTransformer($transformer)
                )->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));

        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($entityManager) {
                    $form = $event->getForm();

                    $formOptions = array(
                    'attr' => array('class' => 'num_folio'),
                    'choices' => $this->getFolios($event->getData(), $entityManager),
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );
        $builder->addEventListener(
                FormEvents::PRE_BIND, function(FormEvent $event) use ($entityManager) {
                    $form = $event->getForm();

                    $formOptions = array(
                    'attr' => array('class' => 'num_folio'),
                    'choices' => $this->getFolios($event->getData(), $entityManager),
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );
    }

    private function getFolios($data, $em) {
        if (null === $data) {
            return;
        }
        if($data instanceof \Inei\Bundle\PayrollBundle\Entity\Folios){
            $tomo = $data->getTomo();
            if (null === $tomo){
                return;
            }
            $nfolios = $tomo->getFoliosTomo();
        }else{
            $tomo = $data['tomo'];
            $_tomo = $em->getRepository('IneiPayrollBundle:Tomos')->find($tomo);
            if (null === $_tomo){
                return;
            }
            $nfolios = $_tomo->getFoliosTomo();
        }
        
        $folios = array();
        foreach (range(1, $nfolios) as $value) {
            $folios[$value] = 'FOLIO - ' . $value;
        }
        return $folios;
    }

    public function getName() {
        return 'folios';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Folios',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'method' => 'post',
            'attr' => array(
                'style' => 'margin:50px;'
            ),
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
