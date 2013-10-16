<?php

namespace Inei\Bundle\AuthBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ROLES")
 * @ORM\Entity(repositoryClass="Inei\Bundle\AuthBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="Usuarios", mappedBy="roles")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="role", cascade={"persist","remove"})
     */
    private $permissions;
    private $rmpermissions;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->rmpermissions = array();
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    // ... getters and setters for each property

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add users
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $users
     * @return Role
     */
    public function addUser(\Inei\Bundle\AuthBundle\Entity\Usuarios $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Usuarios $users
     */
    public function removeUser(\Inei\Bundle\AuthBundle\Entity\Usuarios $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add permissions
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Permission $permissions
     * @return Role
     */
    public function addPermission(\Inei\Bundle\AuthBundle\Entity\Permission $permissions)
    {
        $permissions->setRole($this);
        $this->permissions[] = $permissions;

        return $this;
    }

    /**
     * Remove permissions
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Permission $permissions
     */
    public function removePermission(\Inei\Bundle\AuthBundle\Entity\Permission $permissions)
    {
        $this->rmpermissions[] = $permissions;
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
    
    public function getRmpermissions()
    {
        return $this->rmpermissions;
    }
    
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
}
