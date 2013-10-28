<?php

namespace Inei\Bundle\PayrollBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of AsginacionService
 *
 * @author holivares
 */
class AsignacionService {

    private $em;
    private $sc;

    public function __construct(EntityManager $em, SecurityContextInterface $sc) {
        $this->em = $em;
        $this->sc = $sc;
    }

    public function asignarTomos($usuario, $tomo) {
        $conn = $this->em->getConnection();
        try {
            $conn->beginTransaction();
            $user = $this->sc->getToken()->getUser()->getId();
            $date = new \DateTime();
            $_date= $date->format('Y-m-d H:i:s');
//            $smt = $conn->prepare('SELECT co_tomo FROM asignacion WHERE co_asignado=:co_asignado');
//            $smt->bindValue('co_asignado', $usuario);
//            $smt->execute();
//            $current = $smt->fetchAll();
//            $tomosasignados = array_map(
//                    create_function('$item', 'return $item["co_tomo"];'), $current);
//            $result = array_diff($tomos, $tomosasignados);
            $sql = "INSERT INTO asignacion(
            fe_asignacion, co_asignado, co_tomo, co_asignador)
                VALUES (:fe_asignacion, :co_asignado, :co_tomo, :co_asignador);
            ";
//            foreach ($result as $tomo) {
            $smt = $conn->prepare($sql);
            $smt->bindValue('fe_asignacion', $_date);
            $smt->bindValue('co_asignado', $usuario);
            $smt->bindValue('co_tomo', $tomo);
            $smt->bindValue('co_asignador', $user);
            $smt->execute();
//            }
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    public function desasignarTomos($usuario, $tomo) {
        if(!$usuario & !$tomo)
            return false;
//        $conn = $this->em->getConnection();
//        $smt = $conn->prepare('SELECT co_tomo FROM asignacion WHERE co_asignado=:co_asignado');
//        $smt->bindValue('co_asignado', $usuario);
//        $smt->execute();
//        $current = $smt->fetchAll();
        //COMPROBAR QUE YA ESTA EN LA LISTA
        $q = $this->em->createQuery('delete from IneiPayrollBundle:Asignacion a 
            where a.tomo = '.$tomo.' and a.asignado= '.$usuario );
//                ->setParameter('tomo', $tomo)
//                ->setParameter('asignado', $usuario);
        return $q->execute();
    }
    
}
