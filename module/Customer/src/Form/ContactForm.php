<?php
namespace Customer\Form;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Filter\StripTags;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;
use Zend\Validator\StringLength;

/**
 * Created by PhpStorm.
 * User: Thuan Nguyen
 * Date: 5/4/2018
 * Time: 3:15 PM
 */

class ContactForm extends Form {
    public function __construct($name = null, array $options = [])
    {
        parent::__construct('contact-form');

        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', '/customer/contact');

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements()
    {

        $this->add([
            'type' => Text::class,
            'name' => 'email',
            'attributes' => [
                'id' => 'email'
            ],
            'options' => [
                'label' => 'Email'
            ]
        ]);

        /*$element = new Text('subject', [
            'label' => 'Subject'
        ]);

        $element->setAttribute('id', 'subject');

        $this->add($element);*/

        $this->add([
            'type' => Text::class,
            'name' => 'subject',
            'options' => [
                'label' => 'Subject'
            ]
        ]);


        $this->add([
            'type' => Text::class,
            'name' => 'body',
            'attributes' => [
                'id' => 'body'
            ],
            'options' => [
                'label' => 'Message Body'
            ]
        ]);

        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Submit'
            ]
        ]);
    }

    private function addInputFilter() {

        $inputFilter = new InputFilter();

        $this->setInputFilter($inputFilter);

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
                    'name' => EmailAddress::class,
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'subject',
            'required' => true,
            'filters' => [
                [
                    'name' => StringTrim::class
                ],
                [
                    'name' => StripTags::class
                ],
                [
                    'name' => StripNewlines::class
                ]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'body',
            'required' => true,
            'filters' => [
                [
                    'name' => StripTags::class
                ]
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ]
                ]
            ]
        ]);
    }
}
