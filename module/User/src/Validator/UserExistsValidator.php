<?php
namespace User\Validator;

use User\Entity\User;
use Zend\Validator\AbstractValidator;

class UserExistsValidator extends AbstractValidator {

    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'user' => null
    ];

    // Validation failure message IDs.
    const NOT_SCALAR = 'notScalar';
    const USER_EXISTS = 'userExists';

    protected $messageTemplates = [
        self::NOT_SCALAR => "The email must be a scalar value",
        self::USER_EXISTS => "Another user with such an email already exists"
    ];

    /**
     * UserExistsValidator constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {
        // set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if (isset($options['user']))
                $this->options['user'] = $options['user'];
        }
        // call the parent class constructor
        parent::__construct($options);
    }

    /**
     * Check if user exists.
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value) {
        if (!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        // Get doctrine entity manager.
        $entityManager = $this->options['entityManager'];

        $user = $entityManager->getRepository(User::class)
            ->findOneByEmail($value);

        if ($this->options['user'] == null) {
            $isValid = ($user==null);
        } else {
            if ($this->options['user']->getEmail()!= $value && $user!=null)
                $isValid = false;
            else
                $isValid = true;
        }

        // If there were an error, set error message.
        if (!$isValid)
            $this->error(self::USER_EXISTS);

        // return validation result
        return $isValid;
    }
}