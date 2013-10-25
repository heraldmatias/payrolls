<?php

// src/Acme/UserBundle/Entity/User.php

namespace Inei\Bundle\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Inei\Bundle\AuthBundle\Entity\User
 *
 * @ORM\Table(name="USUARIOS")
 * @ORM\Entity(repositoryClass="Inei\Bundle\AuthBundle\Repository\UsuariosRepository")
 */
class Usuarios implements AdvancedUserInterface, \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="NOM_COMP_USU", type="string", length=150)
     */
    private $nombres;

    /**
     * @ORM\Column(name="COD_USU", type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="SALT_USU", type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(name="PASS_USU", type="string", length=128)
     */
    private $password;

    /**
     * @ORM\Column(name="EMAIL_USU", type="string", length=60, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;
    
    private $permissions;

    public function getRoles() {
        $roles = $this->roles->toArray();
        $roles[] = 'ROLE_USER';
        return $roles;
    }
    
    public function __toString() {
        return $this->username;
    }

    public function __construct() {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();
    }

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->password;
    }

//    public function getRoles() {
//        return array('ROLE_ADMIN');
//    }

    public function getSalt() {
        return '';
    }

    public function getUsername() {
        return $this->username;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return Usuarios
     */
    public function setNombres($nombres) {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres() {
        return $this->nombres;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Usuarios
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuarios
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuarios
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuarios
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Usuarios
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function isActive() {
        return $this->isActive;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive();
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Add roles
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Role $roles
     * @return Usuarios
     */
    public function addRole(\Inei\Bundle\AuthBundle\Entity\Role $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Role $roles
     */
    public function removeRole(\Inei\Bundle\AuthBundle\Entity\Role $roles) {
        $this->roles->removeElement($roles);
    }

    public function getRolesCollection() {
        return $this->roles;
    }
    
    public function getActiveDisplay(){
        return $this->isActive ? 'ACTIVO' : 'INACTIVO';
    }
    
    public function setPermissions(array $permissions){
        $this->permissions = $permissions;
        
        return $this;
    }
    
    public function getPermissions(){
        return $this->permissions;
    }

}
