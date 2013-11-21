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
            $sql = "INSERT INTO asignacion(
            fe_asignacion, co_asignado, co_tomo, co_asignador)
                VALUES (:fe_asignacion, :co_asignado, :co_tomo, :co_asignador);
            ";
            $smt = $conn->prepare($sql);
            $smt->bindValue('fe_asignacion', $_date);
            $smt->bindValue('co_asignado', $usuario);
            $smt->bindValue('co_tomo', $tomo);
            $smt->bindValue('co_asignador', $user);
            $smt->execute();
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
        //COMPROBAR QUE YA ESTA EN LA LISTA
        $q = $this->em->createQuery('delete from IneiPayrollBundle:Asignacion a 
            where a.tomo = '.$tomo.' and a.asignado= '.$usuario );
        return $q->execute();
    }
    
    public function resasignarTomos($usuarioactual, $nuevousuario, $tomo) {
        if(!$usuarioactual | !$tomo | !$nuevousuario)
            return false;
        //COMPROBAR QUE YA ESTA EN LA LISTA
        $conn = $this->em->getConnection();
        try {
            $conn->beginTransaction();
            $user = $this->sc->getToken()->getUser()->getId();
            $date = new \DateTime();
            $_date= $date->format('Y-m-d H:i:s');
            $sql = "UPDATE asignacion
                SET fe_modifica_asig = :fe_modifica_asig,
                co_asignado = :nuevo_usuario,
                co_tomo= :co_tomo,
                co_modificador=:co_modificador
                WHERE co_asignado=:usuario_actual and co_tomo=:co_tomo;
            ";
            $smt = $conn->prepare($sql);
            $smt->bindValue('fe_modifica_asig', $_date);
            $smt->bindValue('nuevo_usuario', $nuevousuario);
            $smt->bindValue('co_tomo', $tomo);
            $smt->bindValue('co_modificador', $user);
            $smt->bindValue('usuario_actual', $usuarioactual);
            $smt->execute();
            $conn->commit();
            return true;
        } catch (Doctrine\DBAL\DBALException $e) {
            $conn->rollBack();
            return false;
        }
    }
}
