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
                ->add('registro', 'hidden', array(
                ))
                ->add('codigos', 'hidden', array(
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
                    /*
                     * SE LEEN LOS CONCEPTOS DEL FOLIO PARA 
                     * CONSTRUIR LAS COLUMNAS Y FILAS DE LA PLANILLA
                     */
                    $egr = 'border-color: #e9322d; -webkit-box-shadow: 0 0 6px #f8b9b7; -moz-box-shadow: 0 0 6px #f8b9b7; box-shadow: 0 0 6px #f8b9b7;';
                    $ing = 'border-color: #e9322d; -webkit-box-shadow: 0 0 6px #2D78E9; -moz-box-shadow: 0 0 6px #2D78E9; box-shadow: 0 0 6px #2D78E9;';
                    foreach ($folio->getConceptos() as $value) {
                        $concepto = $value->getCodiConcTco();
                        $tomo = $folio->getTomo();
                        $codigoConcepto = $concepto->getCodiConcTco();
                        $tipo = $concepto->getTipoConcTco();
                        $tipoCaja = 'text';
                        $formOptions['label'] = $codigoConcepto;
                        $class = 'monto';
                        if($tomo->getCodiTomo()>=89){
                        switch ($codigoConcepto) {
                            case 'C373':
                                $class = 'remuneraciones';
                                break;
                            case 'C374':
                                $class = 'descuentos';
                                break;
                            case 'C12':
                                $class = 'total';
                                break;
                            default:
                                $class = 'monto';
                                break;
                        }}
                        //echo in_array($tipo, array(1,2));
//                        if($tipo === '1' || $tipo === '2')
//                            $tipoCaja = 'integer';
                        /*
                         * ATRIBUTOS PARA CADA CAMPO DE CONCEPTO A DIGITAR
                         */
                        
                        $formOptions['attr'] = array(
                            'data-title' => $concepto->getDescCortTco(),
                            'data-tipo' => $tipo,
                            'class' => $class,
                            'style' => 'width:auto;font-size:15px;'.($tipo==='1'?$ing:$egr),
                            'maxlength' => 35
                        );
                        $formOptions['attr']['placeholder'] = $concepto->getDescCortTco();
                        /*
                         * CORRELATIVO PARA CONCEPTOS REPETIDOS EN LA 
                         * PLANILLA
                         */
                        if(array_key_exists($concepto->getCodiConcTco(), $campos)){
                            $campos[$concepto->getCodiConcTco()] += 1;
                        } else {
                            $campos[$concepto->getCodiConcTco()] = 1;
                        }
                        /*
                         * SE AGREGA EL CAMPO AL FORMULARIO
                         */
                        $index = $campos[$concepto->getCodiConcTco()];
                        $flag = '_'.$index;//($index>1)?'_'.$index:'';
                        $form->add($concepto->getCodiConcTco().$flag, $tipoCaja, $formOptions);
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
        ));

        $resolver->setRequired(array(
            'folio',
        ));

        $resolver->setAllowedTypes(array(
            'folio' => 'Inei\Bundle\PayrollBundle\Entity\Folios',
        ));
    }

}
