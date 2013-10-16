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
    
    public function __construct(EncoderFactoryInterface $factory,
             SecurityContextInterface $sc, EntityManager $em)
    {
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
    
    public function hasPermission($module, $type){
        $user = $this->sc->getToken()->getUser();
        $perm = $this->em->getRepository('IneiAuthBundle:Permission')
                ->getByUser($user->getId(), $module, $type);
        //print_r(count($perm) == 0);
    //echo $perm;
        return count($perm);//AND :perm in p.type 
    }

}