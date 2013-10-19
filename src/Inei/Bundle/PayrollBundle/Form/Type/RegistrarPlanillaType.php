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
// src/Inei/Bundle/PayrollBundle/Form/Type/RegistrarPlanillaType.php

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RegistrarPlanillaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entityManager = $options['em'];
        $self = $this;
        $builder->add('tomo', 'choice', array(
                    'attr' => array('style' => 'width:100%'),
                    'empty_value' => 'SELECCIONE',
                    'label' => 'Tomo',
                    'choices' => array_combine(range(1, 419),range(1, 419)),
                    'required' => false
                ))
                ->add('buscar', 'submit', array(
                    'label' => 'Consultar',
                    'attr' => array('class' => 'btn btn-primary'),));

        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function(FormEvent $event) 
                    use ($entityManager, $self) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'attr' => array('style' => 'width:100%'),
                        'empty_value' => 'SELECCIONE',
                        'choices' => $self->getFolios($event->getData(), $entityManager),
                        'required' => false
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );
        $builder->addEventListener(
                FormEvents::PRE_BIND, function(FormEvent $event)
                use ($entityManager, $self) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'attr' => array('style' => 'width:100%'),
                        'empty_value' => 'SELECCIONE',
                        'choices' => $self->getFolios($event->getData(), $entityManager),
                        'required' => false,                        
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );        
        
    }
    
    public function getFolios($data, $em) {
        if (null === $data) {
            return;
        }        
        $folios = array();
        if(array_key_exists('tomo', $data))
        {
            $tomo = is_numeric($data['tomo'])?$data['tomo']:0;
            $_tomo = $em->getRepository('IneiPayrollBundle:Tomos')->find($tomo);
            if (null === $_tomo){
                return;
            }
            $nfolios = $_tomo->getFoliosTomo();
            foreach (range(1, $nfolios) as $value) {
                $folios[$value] = $value;
            }
        }
        return $folios;
    }

    public function getName() {
        return 'registrar_planilla';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'method' => 'post',
            'attr' => array(
                'id' => 'form_consulta'
            )
            // a unique key to help generate the secret token
            
        ));

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

}