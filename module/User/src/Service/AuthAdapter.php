<?php
namespace User\Service;
use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:31 AM
 */

class AuthAdapter implements AdapterInterface {

    /**
     * User email
     * @var string
     */
    private $email;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Entity Manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AuthAdapter constructor.
     * @param $entityManager
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Set email
     * @param $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Set password
     * @param $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    public function authenticate()
    {
        // Check the database if there is a user with such email.
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($this->email);

        // If there is no such user, return 'Identify Not Found' status.
        if ($user == null)
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']
            );

        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired user to log in.
        if ($user->getStatus() == User::STATUS_RETIRED)
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']
            );

        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($this->password, $passwordHash))
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']
            );

        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']
        );
    }
}