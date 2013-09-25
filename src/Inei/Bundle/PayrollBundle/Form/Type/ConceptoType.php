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

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codiCiclCic', null, array(
                    'label' => 'Codigo'
                ))
                ->add('descConcTco', null, array(
                    'label' => 'Descripcion'
                ))
                ->add('descCortTco', null, array(
                    'label' => 'Descripcion Corta'
                ))
                ->add('tipoConcTco', 'choice', array(
                    'label' => 'Tipo Concepto',
                    'choices' => array(
                        0 => 'Tiempo', 1 => 'Ingresos', 2 => 'Egresos', 3 => 'Aportaciones', 4 => 'Otros'
                    )
                ))
                ->add('tipoCalcTco', 'choice', array(
                    'label' => 'Tipo Calculo',
                    'choices' => array(
                        0 => 'Otros', 1 => 'Fijo', 2  => 'Formula'
                    )
                ))
                ->add('secuCalcTco', null, array())
                ->add('flagAsocTco', null, array())
                ->add('flagRecuTco', null, array(
                    'label' => 'Flag'
                ))
                ->add('rntaQntaTco', null, array(
                    'label' => 'Renta Quinta'
                ))
                ->add('ctsCtsTco', null, array(
                    'label' => 'Seleccion de CTS'
                ))
                ->add('codiConcOnc', null, array())
                ->add('codiEntiEnt', null, array(
                    
                ))
                ->add('cntaDebeTco', null, array(
                    'label' => 'Cuenta Debe'
                ))
                ->add('cntaHabeTco', null, array(
                    'label' => 'Cuenta Haber'
                ))
                ->add('clasConcTco', 'choice', array(
                    'choices' => array(
                        1 => 'Fijo', 2 => 'Variable'
                    )
                ))
                ->add('flagPagoTco', 'choice', array(
                    'choices' => array(
                        1 => 'INEI', 2 => 'MEF'
                    ),
                    'label' => 'Pago'
                ))
                ->add('sedeConcTco', 'choice', array(
                    'choices' => array(
                        0 => 'Lima', 1 => 'ODEIS'
                    ),
                    'label' => 'Sede'
                ))
                ->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),))
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Guardar y Añadir Otro',
                    'attr' => array('class' => 'btn btn-primary')));
    }

    public function getName() {
        return 'concepto';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Conceptos',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'method' => 'post',
            'intention' => 'task_item',
        ));
    }

}
