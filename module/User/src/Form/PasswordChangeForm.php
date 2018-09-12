<?php
namespace User\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

/**
 * Class PasswordChangeForm is used when changing user's password (to collect user's old password
 * and new password) or when resetting user's password (when user forgot his password)
 * @package User\Form
 */
class PasswordChangeForm extends Form {
    // There can be two scenarios - "change" or "reset"
    private $scenario;

    public function __construct($name = null, array $options = [])
    {
        // Define form name
        $name = 'password-change-form';
        parent::__construct($name, $options);

        // Set POST method for this form.
        $this->setAttribute('method', 'POST');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input field and submit button).
     */
    protected function addElements() {
        // If scenario is "change", we do not ask for old password.
        if ($this->scenario == 'change') {

            // Add "old_password" field.
            $this->add([
                'type' => Password::class,
                'name' => 'old_password',
                'options' => [
                    'label' => 'Old Password'
                ]
            ]);
        }

        // Add "new_password" field.
        $this->add([
            'type' => Password::class,
            'name' => 'new_password',
            'options' => [
                'label' => 'New Password',
            ]
        ]);

        // Add "confirm_new_password" field.
        $this->add([
            'type' => Password::class,
            'name'=> 'confirm_new_password',
            'options' => [
                'label' => 'Confirm New Password'
            ]
        ]);

        // Add CSRF field
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
                'value' => 'Change Password'
            ]
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation)
     */
    protected function addInputFilter() {
        // Create main input filter.
        $inputFilter = $this->getInputFilter();

        if ($this->scenario == 'change') {

            // Add input for "old_password" filed.
            $inputFilter->add([
                'name' => 'old_password',
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
        }

        // Add input for "new_password" field
        $inputFilter->add([
            'name' => 'new_password',
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

        // Add input for "confirm_new_password" field.
        $inputFilter->add([
            'name' => 'confirm_new_password',
            'required' => true,
            'filters' => [],
            'validators' => [
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => 'new_password'
                    ]
                ]
            ]
        ]);
    }
}