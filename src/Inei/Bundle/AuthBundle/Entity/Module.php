<?php

namespace Inei\Bundle\AuthBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Module")
 * @ORM\Entity(repositoryClass="Inei\Bundle\AuthBundle\Repository\ModuleRepository")
 */
class Module implements \Serializable {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="name", type="string", length=15, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="string", length=70, nullable=false)
     */
    private $description;
    
    /**
     * @ORM\Column(name="query_path", type="string", length=30, nullable=true)
     */
    private $queryPath;
    
    /**
     * @ORM\Column(name="add_path", type="string", length=30, nullable=true)
     */
    private $addPath;
    
    /**
     * @ORM\Column(name="order", type="integer", nullable=true)
     */
    private $order;
    
    /**
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="module")
     */
    private $permissions;

    public function __construct() {
        $this->roles = ArrayCollection();
    }

    public function __toString() {
        return $this->getDescription();
    }
    
    /**
     * Set type
     *
     * @param string $type
     * @return Permission
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add roles
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Role $roles
     * @return Permission
     */
    public function addRole(\Inei\Bundle\AuthBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Role $roles
     */
    public function removeRole(\Inei\Bundle\AuthBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Module
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add permissions
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Permission $permissions
     * @return Module
     */
    public function addPermission(\Inei\Bundle\AuthBundle\Entity\Permission $permissions)
    {
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
    
    /**
     * Set name
     *
     * @param string $name
     * @return Module
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

    public function serialize() {
        return serialize(array(
            $this->name,
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->name,
                ) = unserialize($serialized);
    }
    
    

    /**
     * Set queryPath
     *
     * @param string $queryPath
     * @return Module
     */
    public function setQueryPath($queryPath)
    {
        $this->queryPath = $queryPath;

        return $this;
    }

    /**
     * Get queryPath
     *
     * @return string 
     */
    public function getQueryPath()
    {
        return $this->queryPath;
    }

    /**
     * Set addPath
     *
     * @param string $addPath
     * @return Module
     */
    public function setAddPath($addPath)
    {
        $this->addPath = $addPath;

        return $this;
    }

    /**
     * Get addPath
     *
     * @return string 
     */
    public function getAddPath()
    {
        return $this->addPath;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Module
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }
}
