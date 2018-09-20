<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class for mapping Role table.
 * @ORM\Entity()
 * @ORM\Table(name="role")
 */
class Role {

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="role_hierarchy",
     *     joinColumns{@ORM\JoinColumn(name="child_role_id", referencedColumnName="id"},
     *     inverseJoinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")}
     *     )
     */
    private $parentRoles;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="role_hierarchy",
     *     joinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="child_role_id", referencedColumnName="id")}
     *     )
     */
    private $childRoles;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Permission)
     * @ORM\JoinTable(name="role_permission",
     *     joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     *     )
     */
    private $permissions;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->parentRoles = new ArrayCollection();
        $this->childRoles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * Return Role ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set Role ID.
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get Role Name.
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set Role Name.
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get description.
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set Description.
     * @param $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get Date Created.
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * Set Date Created.
     * @param $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * Get Parent Roles.
     * @return ArrayCollection
     */
    public function getParentRoles() {
        return $this->parentRoles;
    }

    /**
     * Get Child Roles.
     * @return ArrayCollection
     */
    public function getChildRoles() {
        return $this->childRoles;
    }

    /**
     * Get Permissions.
     * @return ArrayCollection
     */
    public function getPermissions() {
        return $this->permissions;
    }

    public function addParent(Role $role) {
        if ($this->getId() == $role->getId()) {
            return false;
        }
        if (!$this->hasParent($role)) {
            $this->parentRoles[] = $role;
            return true;
        }
        return false;
    }

    public function clearParentRoles() {
        $this->parentRoles = new ArrayCollection();
    }

    public function hasParent(Role $role) {
        if ($this->getParentRoles()->contains($role)) {
            return true;
        }
        return false;
    }
}