<?php

namespace User\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;
use Zend\Validator\InArray;
use Zend\Validator\StringLength;

/**
 * Class LoginForm is used to collect user's login, password and 'Remember Me' flag.
 * @package User\Form
 */
class LoginForm extends Form
{

    public function __construct($name = null, array $options = [])
    {
        //Define form name.
        $name = 'login-form';
        parent::__construct($name, $options);

        // Set POST method for this form.
        $this->setAttribute('method', 'POST');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input filters and submit button).
     */
    public function addElements()
    {
        // Add "email" field.
        $this->add([
            'type' => Text::class,
            'name' => 'email',
            'options' => [
                'label' => 'Your E-mail'
            ]
        ]);
        // Add "password" field.
        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password'
            ]
        ]);
        // Add "remember_me" field.
        $this->add([
            'type' => Checkbox::class,
            'name' => 'remember_me',
            'options' => [
                'label' => 'Remember me'
            ]
        ]);
        // Add "redirect_url" field.
        $this->add([
            'type' => Hidden::class,
            'name' => 'redirect_url'
        ]);
        // Add the CSRF field.
        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_option' => [
                    'timeout' => 600
                ]
            ]
        ]);
        // Add the submit button.
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Sign in',
                'id' => 'submit'
            ]
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    public function addInputFilter() {
        // Create main input filter.
        $inputFilter = $this->getInputFilter();

        // Add input for "email" field.
        $inputFilter->add([
            'name' => Email::class,
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class]
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false
                    ]
                ]
            ]
        ]);

        // Add input for "password" field.
        $inputFilter->add([
            'name' => Password::class,
            'required' => true,
            'filters' => [],
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

        // Add input for "remember_me" field.
        $inputFilter->add([
            'name' => 'remember_me',
            'required'=> false,
            'filters' => [],
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => [0, 1]
                    ]
                ]
            ]
        ]);

        // Add input for "redirect_url" field.
        $inputFilter->add([
            'name' => 'redirect_url',
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 0,
                        'max' => 2048
                    ]
                ]
            ]
        ]);
    }
}