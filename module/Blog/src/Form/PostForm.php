<?php
namespace Blog\Form;

use Zend\Form\Form;

class PostForm extends Form {
    public function init() {
        $this->add([
            'name' => 'post',
            'type' => PostFieldSet::class
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Insert new Post',
            ]
        ]);
    }
}