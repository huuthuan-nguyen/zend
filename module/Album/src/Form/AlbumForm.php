<?php
namespace Album\Form;
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 5:26 PM
 */
use Zend\Form\Form;

class AlbumForm extends Form {
    public function __construct($name = null, array $options = [])
    {
        parent::__construct('album', $options);

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
    }
}