<?php

namespace Inei\Bundle\AuthBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of UsuarioService
 *
 * @author holivares
 */
class UsuarioService {

    private $factory;
    private $em;
    private $sc;

    public function __construct(EncoderFactoryInterface $factory, SecurityContextInterface $sc, EntityManager $em) {
        $this->factory = $factory;
        $this->em = $em;
        $this->sc = $sc;
    }

    public function encodePassword($user) {
        $encoder = $this->factory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        return $user;
    }

    public function hasPermission($module, $type) {
//        $user = $this->sc->getToken()->getUser();
//        $perm = $this->em->getRepository('IneiAuthBundle:Permission')
//                ->getByUser($user->getId(), $module, $type);
//        //print_r(count($perm) == 0);
//    //echo $perm;
//        return count($perm);
        $user = $this->sc->getToken()->getUser();
        $perms = $user->getPermissions();
        $_perm = 0;
        foreach ($perms as $perm) {
            if ($perm['module_id'] === $module) {
                $_perm = in_array($type, $perm['type']);
                break;
            }
        }
        return $_perm;
    }
    
    public function hasRole($role) {
        $user = $this->sc->getToken()->getUser();
        $roles = $user->getRoles();        
        foreach ($roles as $role) {
            $rol = is_object($role)?$role->getRole():$role;
            if ($rol === 'ROLE_ADMINISTRADOR') {
                return true;
            }
        }
        return false;
    }

    public function getPermissions() {
        $user = $this->sc->getToken()->getUser();
        $perms = $this->em->getRepository('IneiAuthBundle:Usuarios')
                ->getPermissions($user->getId());
        return $perms;
    }

    public function listaUsuariosPlanilla($keys = true) {
        return $this->em->getRepository('IneiAuthBundle:Usuarios')
                        ->listaUsuariosPlanilla($keys);
    }

    public function listaTomosAsignados() {
        $user = $this->sc->getToken()->getUser();
        //print_r($user->getRoles());
        if ($this->hasRole('ROLE_SUPERVISOR') | $this->hasRole('ROLE_ADMINISTRADOR')) {
            $tomos = array_combine(range(1, 419), range(1, 419));
        } else {
            $tomos = $this->em->getRepository('IneiAuthBundle:Usuarios')
                    ->listaTomosAsignados($user->getId());
        }
        return $tomos;
    }

}