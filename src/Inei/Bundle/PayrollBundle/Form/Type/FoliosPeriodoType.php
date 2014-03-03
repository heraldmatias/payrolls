<?php

/**
 * Formulario para actualizar los peridos de los folios.
 * 
 * @author holivares
 */

namespace Inei\Bundle\PayrollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class FoliosPeriodoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entityManager = $options['em'];
        $self = $this;
//        $builder->add('tomo', 'choice', array(
//                    'attr' => array('style' => 'width: 100%'),
//                    'empty_value' => '---SELECCIONE---',
//                    'label' => 'Tomo',
//                    'choices' => array_combine(range(1, 419),range(1, 419))
//                ))
                $builder->add('tomo', 'entity', array(
                    'class' => 'IneiPayrollBundle:Tomos',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.codiTomo', 'ASC');
                    }
                ))
                ->add('descFolio', null, array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true,
                    ),
                    'label' => 'Observación'
                ))
                ->add('mesFolio', 'choice', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true
                    ),
                    'empty_value' => '---SELECCIONE---',
                    'label' => 'Mes',
                    'required' => true,
                    'choices' => array(
                        '01' => 'ENERO',
                        '02' => 'FEBRERO',
                        '03' => 'MARZO',
                        '04' => 'ABRIL',
                        '05' => 'MAYO',
                        '06' => 'JUNIO',
                        '07' => 'JULIO',
                        '08' => 'AGOSTO',
                        '09' => 'SETIEMBRE',
                        '10' => 'OCTUBRE',
                        '11' => 'NOVIEMBRE',
                        '12' => 'DICIEMBRE'
                    )
                ))
                ->add('anoFolio', 'choice', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true,
                    ),
                    'empty_value' => '---SELECCIONE---',
                    'choices' => array_combine(range(1960, 1990),range(1960, 1990)),
                    'label' => 'Año'
                ))
                ->add('fecInicio', 'text', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true
                    ),
                    'label' => 'Fecha Inicio'
                ))
                ->add('fecFinal', 'text', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true
                    ),
                    'label' => 'Fecha Fin'
                ))
                ->add('rango', 'choice', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true,
                        'empty_value' => '---SELECCIONE---',
                    ),
                    'label' => 'Rango'
                ))
                ->add('periodoFolio', null, array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true,
                    ),
                    'label' => 'Periodo Folio',
                    'required' => true
                ))
                ->add('tipoFolio', 'choice', array(
                    'attr' => array(
                        'style' => 'width: 100%',
                        'disabled' => true,
                    ),
                    'label' => 'Tipo',
                    'empty_value' => '---SELECCIONE---',
                    'required' => true,
                    'choices' => array(
                        1 => 'MENSUAL',
                        2 => 'QUINCENA',
                        3 => 'INTERMENSUAL',
                        4 => 'INTERDIARIO',
                        5 => 'SEMANAL',
                        6 => 'NO ESPECIFICADO'
                    )
                ))->add('save', 'submit', array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary'),));

        $builder->addEventListener(
                FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($entityManager, $self) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'attr' => array('class' => 'num_folio'),
                        'choices' => $self->getFolios($event->getData(), $entityManager),
                        'required' => true,
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );
        $builder->addEventListener(
                FormEvents::PRE_BIND, function(FormEvent $event) use ($entityManager, $self) {
                    $form = $event->getForm();

                    $formOptions = array(
                        'attr' => array('class' => 'num_folio'),
                        'choices' => $self->getFolios($event->getData(), $entityManager),
                        'required' => true,
                    );
                    $form->add('folio', 'choice', $formOptions);
                }
        );
    }

    public function getFolios($data, $em) {
        if (null === $data) {
            return;
        }
        if ($data instanceof \Inei\Bundle\PayrollBundle\Entity\Folios) {
            $tomo = $data->getTomo();
            if (null === $tomo) {
                return;
            }
            $nfolios = $tomo->getFoliosTomo();
        } else {
            $tomo = $data['tomo'];
            $_tomo = $em->getRepository('IneiPayrollBundle:Tomos')->find($tomo);
            if (null === $_tomo) {
                return;
            }
            $nfolios = $_tomo->getFoliosTomo();
        }

        $folios = array();
        foreach (range(1, $nfolios) as $value) {
            $folios[$value] = $value;
        }
        return $folios;
    }

    public function getName() {
        return 'periodo_folios';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Inei\Bundle\PayrollBundle\Entity\Folios',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'method' => 'post',
            // a unique key to help generate the secret token
            'intention' => 'periodoFolios'
        ));

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

}
