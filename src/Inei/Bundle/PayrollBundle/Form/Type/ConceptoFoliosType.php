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

class ConceptoFoliosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('orden', 'choice', array(
                    'attr' => array('class' => 'ordenconcepto'),
                    'choices' => array_combine(range(1, 20), range(1, 20))
                ))
                ->add('codiConcTco', null, array(
                    'attr' => array('class' => 'codiconcepto')
                ));
//                ->add('descCortTco', null, array(
//                    'read_only' => true
//                ))
//                ->add('tipoConcTco', null, array(
//                    'read_only' => true
//                ));
    }

    public function getName() {
        return 'conceptofolios';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\ConceptosFolios',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention' => 'task_item'            
        ));
    }

}
