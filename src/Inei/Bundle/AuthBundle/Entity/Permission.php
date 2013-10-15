<?php

namespace Inei\Bundle\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 *
 * @ORM\Table(name="Permission")
 * @ORM\Entity(repositoryClass="Inei\Bundle\AuthBundle\Repository\PermissionRepository")
 */
class Permission
{       
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="permissions", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\Id
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=true)
     */
    private $role;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="permissions", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\Id
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id", nullable=true)
     */
    private $module;

    /**
     * @var integer
     * 
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;
    
    public static $PERMISSION_TYPE = array(
        1 => 'Agregar',
        2 => 'Modificar',
        3 => 'Consultar',
        4 => 'Eliminar',
        5 => 'Otro'
    );
    


    /**
     * Set type
     *
     * @param integer $type
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
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set role
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Role $role
     * @return Permission
     */
    public function setRole(\Inei\Bundle\AuthBundle\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set module
     *
     * @param \Inei\Bundle\AuthBundle\Entity\Module $module
     * @return Permission
     */
    public function setModule(\Inei\Bundle\AuthBundle\Entity\Module $module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \Inei\Bundle\AuthBundle\Entity\Module 
     */
    public function getModule()
    {
        return $this->module;
    }
}
