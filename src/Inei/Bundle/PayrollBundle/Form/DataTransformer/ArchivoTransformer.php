<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Inei\Bundle\PayrollBundle\Form\DataTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Inei\Bundle\PayrollBundle\Entity\ExcelTomo;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Description of ArchivoTransformer}
 *
 * @author holivares
 */
class ArchivoTransformer implements DataTransformerInterface {
    
    private $tomo = null;
    
    public function __construct($tomo) {
       $this->tomo = $tomo;
    }

    public function reverseTransform($file) {
        print_r($this->tomo);
//        echo $file;
        if(!null === $this->tomo & null === $file->getFile() ){
            $file->setFile($this->tomo);
        }
//        echo $file;
        return $file;
    }

    public function transform($tomo) {        
        if(null === $tomo){
            return $tomo;
        }
        if(!null == $tomo->getId()){
//            print_r($tomo->getFullFilePath());
            $file = new File($tomo->getFullFilePath());
            //print_r($file);
            $tomo->setFile($file);
        }
        return $tomo;
    }

}
?>
