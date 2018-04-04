<?php
namespace Blog\Form;

use Zend\Form\Fieldset;

class PostFieldSet extends Fieldset {
    public function init() {
        $this->add([
            'type' => 'hidden',
            'name' => 'id'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'title',
            'options' => [
                'label' => 'Post Title'
            ]
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Post Text'
            ]
        ]);
    }
}