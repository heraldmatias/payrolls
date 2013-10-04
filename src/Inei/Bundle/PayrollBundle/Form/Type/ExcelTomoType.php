<?php

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Form\FormEvent;
//use Symfony\Component\Form\FormEvents;
//use Inei\Bundle\PayrollBundle\Form\DataTransformer\ArchivoTransformer;


class ExcelTomoType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        
        $builder
                ->add('title', null, array())
                ->add('description', null, array())
                ->add('file', null, array(
                    'required' => false
                ))//->addModelTransformer($transformer)
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
        
//        $builder->addEventListener(
//                FormEvents::PRE_SET_DATA, function(FormEvent $event) {
//                    $form = $event->getForm();
//                    $card = $event->getData();
//                    if(null === $card->getFullFilePath()){
//                        $form->add('file', 'file', array());
//                    }else{
//                        $form->add('file', 'file', array(
//                            'required' => false
//                        ));
//                    }
//                }
//        );
//        
//        $builder->addEventListener(
//                FormEvents::PRE_BIND, function(FormEvent $event) {
//                    $form = $event->getForm();
//                    $card = $event->getData();
//                    if(null === $card | is_array($card) ){
//                        return;
//                    }
//                    if(null === $card->getFullFilePath()){
//                        $form->add('file', 'file', array());
//                    }else{
//                        $form->add('file', 'file', array(
//                            'required' => false
//                        ));
//                    }
//                    
//                }
//        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\ExcelTomo'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'exceltomo';
    }

}
