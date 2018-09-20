<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a registered user.
 * @ORM\Entity(repositoryClass="\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User {
    // User status constants
    const STATUS_ACTIVE = 1; // Active user.
    const STATUS_RETIRED = 2; // Retired user.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="full_name")
     */
    protected $fullName;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @ORM\Column(name="pwd_reset_token")
     */
    protected $passwordResetToken;

    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")
     */
    protected $passwordResetTokenCreationDate;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * Return User ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set User ID
     * @param $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Return Email
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set Email.
     * @param $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Return full name.
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * Set full name.
     * @param $fullName
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }

    /**
     * Set status.
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set status.
     * @param $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Return possible statuses as array.
     * @return array
     */
    public static function getStatusList() {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * Return user status as string
     * @return mixed|string
     */
    public function getStatusAsString() {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * Return password.
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set password.
     * @param $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Return the date of user creation.
     * @return string
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * Set the date when this user was created.
     * @param $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * Return password reset token.
     * @return string
     */
    public function getPasswordResetToken() {
        return $this->passwordResetToken;
    }

    /**
     * Set password reset token.
     * @param $token
     */
    public function setPasswordResetToken($token) {
        $this->passwordResetToken = $token;
    }

    /**
     * Return password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate() {
        return $this->passwordResetTokenCreationDate;
    }

    /**
     * Set password reset token's creation date.
     * @param $date
     */
    public function setPasswordResetTokenCreationDate($date) {
        $this->passwordResetTokenCreationDate = $date;
    }

    /**
     * Return the array of roles assigned to this user.
     * @return mixed
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the string of assigned role names.
     * @return string
     */
    public function getRolesAsString() {
        $roleList = '';

        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();

            if ($i < $count-1)
                $roleList .= ', ';
            $i++;
        }
        return $roleList;
    }

    /**
     * Assign a role to user.
     * @param $role
     */
    public function addRole($role) {
        $this->roles->add($role);
    }
}
