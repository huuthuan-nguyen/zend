<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Permission
 * @ORM\Entity()
 * @ORM\Table(name="permission")
 */
class Permission {

    /**
     * @ORM\Id
     * @ORM\Column(name="id)
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
     * @ORM\JoinTable(name="role_permission",
     *     joinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id"},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id"}
     *     )
     */
    private $roles;

    /**
     * Permission constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * Get Permission ID.
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set Permission ID.
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get Permission Name.
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set Permission Name.
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get Permission Description
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set Permission Description
     * @param $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get Permission Date Created
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * Set Permission Date Created
     * @param $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * Get Roles belong to this permission.
     * @return ArrayCollection
     */
    public function getRoles() {
        return $this->roles;
    }
}