<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 8/22/2018
 * Time: 10:31 AM
 */

class UserManager {

    /**
     * Doctrine entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * PHP template renderer.
     * @var
     */
    private $viewRenderer;

    /**
     * Application Config
     * @var
     */
    private $config;

    public function __construct($entityManager, $viewRenderer, $config) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * Add a new user.
     * @param $data
     * @return User
     * @throws \Exception
     */
    public function addUser($data) {
        if ($this->checkUserExists($data['email'])) {
            throw new \Exception('User with email address ' . $data['email'] . ' already exists.');
        }

        // Create new User Entity.
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        // Encrypt password and store the password in encrypted state.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);

        $user->setStatus($data['status']);

        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        // Add the entity to the entity manager.
        $this->entityManager->persist($user);

        // Apply changes to database.
        $this->entityManager->flush();
        return $user;
    }

    /**
     * This method updates data of an existing user.
     * @param $user
     * @param $data
     */
    public function updateUser($user, $data) {
        // Do not allow to change user email if another user with such email already exists.
        if ($user->getEmail() != $data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . ' already exists');
        }

        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);
        $user->setStatus($data['status']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * This method checks if at least one user present, and if not, creates
     * 'Admin' user with email 'admin@example.com' and password 'Secur1ty'.
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAdminUserIfNotExists() {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user == null) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Scur1ty');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * Checks whether an active user with given email address already exists in the database.
     * @param $email
     * @return bool
     */
    public function checkUserExists($email) {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        return $user !== null;
    }

    /**
     * Check if the given password is correct.
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function validatePassword(User $user, string $password) {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash))
            return true;

        return false;
    }

    /**
     * Generates a password reset token for the user. This token is then store in database and
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is
     * direacted to the Set Password page.
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generatePasswordResetToken(User $user) {
        if ($user->getStatus() != User::STATUS_ACTIVE)
            throw new \Exception('Can not generate password reset token for inactive user '. $user->getEmail());

        // Generate a token.
        $token = Rand::getString('32', '0123456789abcdefghijklmnopqrstuvwxyz', true);

        // Encrypt the token before storing it in DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        // Save token to DB
        $user->setPasswordResetToken($tokenHash);

        // Save token creation date to DB.
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);

        // Apply changes to DB.
        $this->entityManager->flush();

        // Send an email to user.
        $subject = 'Password Reset';

        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();

        // Produce HTML of password reset email.
        $bodyHtml = $this->viewRenderer->render(
            'user/email/reset-email-password',
            [
                'passwordResetUrl' => $passwordResetUrl
            ]
        );

        $html = new MimePart($bodyHtml);
        $html->type = 'text/html';
        $body = new MimeMessage();
        $body->addPart($html);

        $mail = new Mail\Message();
        $mail->setEncoding('UTF-8');
        $mail->setBody($body);
        $mail->setFrom('no-reply@example.com', 'User Demo');
        $mail->addTo($user->getEmail(), $user->getFullName());
        $mail->setSubject($subject);

        // Setup SMTP transport
        $transport = new SmtpTransport();
        $options = new SmtpOptions($this->config['smtp']);
        $transport->setOptions($options);

        $transport->send($mail);
    }

    /**
     * Check whether the given password reset token is a valid one.
     *
     * @param $email
     * @param $passwordResetToken
     * @return bool
     */
    public function validatePasswordResetToken($email, $passwordResetToken) {
        // Find user by email.
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);

        if ($user == null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Check that token hash matches the token hash in our DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $user->getPasswordResetToken();

        if (!$bcrypt->verify($passwordResetToken, $tokenHash))
            return false; // mismatch

        // Check that token was created not too long ago.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if ($currentDate - $tokenCreationDate > 24*60*60)
            return false; // expired.

        return true;
    }

    /**
     * This method sets new password by password reset token.
     *
     * @param $email
     * @param $passwordResetToken
     * @param $newPassword
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setNewPasswordByToken($email, $passwordResetToken, $newPassword) {
        if (!$this->validatePasswordResetToken($email, $passwordResetToken)) {
            return false;
        }

        // Find use with the given email.
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);

        if ($user == null || $user->getStatus() != User::STATUS_ACTIVE)
            return false;

        // Set new password for user.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Remove password reset token.
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);

        $this->entityManager->flush();

        return true;
    }

    public function changePassword($user, $data) {
        $oldPassword  = $data['old_password'];

        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }

        $newPassword = $data['new_password'];

        // Check password length
        if (strlen($newPassword) < 6 || strlen($newPassword) > 64) {
            return false;
        }

        // set new password for user.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Apply changes
        $this->entityManager->flush();

        return true;
    }
}