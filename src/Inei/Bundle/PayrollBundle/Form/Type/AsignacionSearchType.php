<?php

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Inei\Bundle\AuthBundle\Service\UsuarioService;

/**
 * Description of AsignacionType
 *
 * @author holivares
 */
class AsignacionSearchType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tomo', 'number', array(
                    'label' => 'Tomo',                    
                    'attr' => array(
                        'style' => 'width: 100%',
                        'size' => '10'
                    ),
                    'required' => false
                ))
                ->add('add', 'button', array(
                    'label' => 'Consultar',
                    'attr' => array('class' => 'btn btn-primary'),));
//                ->add('save', 'submit', array(
//                    'label' => 'Guardar',
//                    'attr' => array('class' => 'btn btn-primary'),))
//                ->add('saveAndAdd', 'submit', array(
//                    'label' => 'Guardar y Añadir Otro',
//                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'asignacion_search';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            #'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Asignacion',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}