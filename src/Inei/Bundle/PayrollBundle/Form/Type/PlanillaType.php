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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlanillaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $folio = $options['folio'];
        $builder->add('codiEmplPer', 'text', array(
                    'attr' => array(
                        'data-title' => 'NOMBRES Y APELLIDOS',
                        'class' => 'nombre',
                        'style' => 'width:200px;',
                        'autocomplete' => 'on',
                        'data-provide' => 'typeahead'
                    ),
                    'max_length' => 100
                ))
                ->add('descripcion', 'textarea', array(
                    'attr' => array(
                        'data-title' => 'OBSERVACION',
                        'class' => 'descripcion',
                        'style' => 'width:200px;'
                    ),
                    'required' => false
                ));
        
        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($folio) {
                    $form = $event->getForm();
                    $campos = array();
                    $formOptions = array();
                    foreach ($folio->getConceptos() as $value) {
                        $concepto = $value->getCodiConcTco();
                        $formOptions['label'] = $concepto->getCodiConcTco();
                       // $formOptions['data'] = '0';
                        $formOptions['attr'] = array(
                            'data-title' => $concepto->getDescCortTco(),
                            'class' => 'monto',
                            'style' => 'width:150px;font-size:10px;'
                        );
                        $formOptions['attr']['placeholder'] = $concepto->getDescCortTco();

                        if(array_key_exists($concepto->getCodiConcTco(), $campos)){
                            $campos[$concepto->getCodiConcTco()] += 1;
                        } else {
                            $campos[$concepto->getCodiConcTco()] = 1;
                        }
                        $index = $campos[$concepto->getCodiConcTco()];
                        $flag = '_'.$index;//($index>1)?'_'.$index:'';
                        $form->add($concepto->getCodiConcTco().$flag, 'text', $formOptions);
                    }
                    
                }
        );
    }

    public function getName() {
        return 'planilla';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //'data_class' => 'Inei\Bundle\PayrollBundle\Entity\PlanillaHistoricas',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'method' => 'post',
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));

        $resolver->setRequired(array(
            'folio',
        ));

        $resolver->setAllowedTypes(array(
            'folio' => 'Inei\Bundle\PayrollBundle\Entity\Folios',
        ));
    }

}
