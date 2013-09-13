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

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codiCiclCic', null, array())
                ->add('descConcTco', null, array())
                ->add('descCortTco', null, array())
                ->add('tipoConcTco', null, array())
                ->add('tipoCalcTco', null, array())
                ->add('secuCalcTco', null, array())
                ->add('flagAsocTco', null, array())
                ->add('flagRecuTco', null, array())
                ->add('rntaQntaTco', null, array())
                ->add('ctsCtsTco', null, array())
                ->add('codiConcOnc', null, array())
                ->add('codiEntiEnt', null, array())
                ->add('cntaDebeTco', null, array())
                ->add('cntaHabeTco', null, array())
                ->add('clasConcTco', null, array())
                ->add('flagPagoTco', null, array())
                ->add('sedeConcTco', null, array())
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
            // a unique key to help generate the secret token
            'intention' => 'task_item',
        ));
    }

}
