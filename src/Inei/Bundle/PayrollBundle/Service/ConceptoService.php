<?php

namespace Inei\Bundle\PayrollBundle\Service;
use Doctrine\ORM\EntityManager;
/**
 * Description of ConceptoService
 *
 * @author holivares
 */
class ConceptoService {
    
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getReporte(array $filtro = null, $values = true) {
        $rows = $this->em->getRepository('IneiPayrollBundle:Conceptos')
                ->reporte($filtro, 'order by c.fec_creac DESC');
        if ($values) {
            $rows = array_map(create_function('$item', 'return array_values($item);'), $rows);
        }
        return $rows;
    }
    
}