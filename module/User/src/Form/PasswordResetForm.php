<?php
namespace User\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Image;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;

class PasswordResetForm extends Form {

    /**
     * PasswordResetForm constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = [])
    {
        // Define form name
        $name = 'password-reset-form';
        parent::__construct($name, $options);

        // set POST method for this form
        $this->setAttribute('method', 'POST');
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    public function addElements() {

        // Add "email" field
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => 'Your Email'
            ]
        ]);

        // Add the CAPTCHA field
        $this->add([
            'type' => Captcha::class,
            'name' => 'captcha',
            'options' => [
                'label' => 'Human Check',
                'captcha' => [
                    'class' => Image::class,
                    'imgDir' => 'public/img/captcha',
                    'suffix' => '.png',
                    'imgUrl' => '/img/captcha',
                    'imgAlt' => 'CAPTCHA Image',
                    'font' => './data/font/thorne_shaded.ttf',
                    'fsize' => 24,
                    'width' => 350,
                    'height' => 100,
                    'expiration' => 600,
                    'dotNoiseLevel' => 40,
                    'lineNoiseLevel' => 3
                ]
            ]
        ]);

        // Add the CSRF field.
        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ]
        ]);

        // Add the Submit button
        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Reset Password',
                'id' => 'submit'
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
            'name' => 'name',
            'required' => true,
            'filters' => [
                [
                    'name' => StringTrim::class
                ]
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ]
                ]
            ]
        ]);
    }
}