<?php
namespace User\Form;

use User\Validator\UserExistsValidator;
use Zend\Filter\StringTrim;
use Zend\Filter\ToInt;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;
use Zend\Validator\Identical;
use Zend\Validator\InArray;
use Zend\Validator\StringLength;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 * @package User\Form
 */
class UserForm extends Form {

    /**
     * Scenario ('create' or 'update')
     * @var string
     */
    private $scenario;

    /**
     * Entity Manager.
     * @var EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var User
     */
    private $user = null;

    /**
     * UserForm constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($scenario = null, $entityManager = null, $user = null)
    {
        // Define form name
        $name = 'user-form';
        parent::__construct($name);

        // set POST method for this form
        $this->setAttribute('method', 'POST');

        // Save parameters for internal user.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() {
        // Add "email" field
        $this->add(
            [
                'type' => Text::class,
                'name' => 'email',
                'options' => [
                    'label' => 'Email'
                ]
            ]
        );

        // Add "full_name" field
        $this->add([
            'type' => Text::class,
            'name' => 'full_name',
            'options' => [
                'label' => 'Full Name'
            ]
        ]);

        if ($this->scenario == 'create') {

            // Add "password" field
            $this->add([
                'type' => Password::class,
                'name' => 'password',
                'options' => [
                    'label' => 'Password'
                ]
            ]);

            // Add "confirm_password" field
            $this->add([
                'type' => Password::class,
                'name' => 'confirm_password',
                'options' => [
                    'label' => 'Confirm password'
                ]
            ]);
        }

        // Add "status" field
        $this->add([
            'type' => Select::class,
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Active',
                    2 => 'Retired',
                ]
            ]
        ]);

        // Add the Submit Button
        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ]
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    public function addInputFilter() {
        // Create main input filter
        $inputFilter = $this->getInputFilter();

        // Add input for "email" field
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ]
                ],
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ]
                ],
                [
                    'name' => UserExistsValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'user' => $this->user
                    ]
                ]
            ]
        ]);

        // Add input for "full_name" field
        $inputFilter->add([
            'name' => 'full_name',
            'required' => true,
            'filters' => [
                'name' => StringTrim::class
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'minx' => 1,
                        'max' => 512
                    ]
                ]
            ]
        ]);

        if ($this->scenario == 'create') {
            // Add input for "password" field
            $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ]
                    ]
                ]
            ]);

            // Add input for "confirm_password" field
            $inputFilter->add([
                'name' => 'confirm_password',
                'required' => true,
                'filters' => [

                ],
                'validators' => [
                    [
                        'name' => Identical::class,
                        'options' => [
                            'token' => 'password',
                        ]
                    ]
                ]
            ]);
        }

        // Add input for "status" field
        $inputFilter->add([
            'name' => 'status',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class]
            ],
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => [1, 2]
                    ]
                ]
            ]
        ]);
    }
}