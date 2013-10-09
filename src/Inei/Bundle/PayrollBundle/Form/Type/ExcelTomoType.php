<?php

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
        
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $this->setFileRequired($form, $data);
                }
        );
        
        $builder->addEventListener(
                FormEvents::PRE_BIND, function(FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $this->setFileRequired($form, $data);
                }
        );
        
    }
    
    private function setFileRequired($form, $data){
        if($data instanceof \Inei\Bundle\PayrollBundle\Entity\ExcelTomo){
            if(null !== $data->getFilename()){
                $form->add('file', null, array(
                    'required' => false
                ));
            }else{
                $form->add('file', null, array(
                    'required' => true
                ));
            }
        }
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
