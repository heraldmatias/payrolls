<?php

namespace Inei\Bundle\AuthBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
/**
 * Description of UsuarioService
 *
 * @author holivares
 */
class UsuarioService {

    private $factory;

    public function __construct(EncoderFactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function encodePassword($user) {
        $encoder = $this->factory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        return $user;
    }

}