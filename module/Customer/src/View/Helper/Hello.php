<?php
namespace Customer\View\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 5/2/2018
 * Time: 5:16 PM
 */

class Hello extends AbstractHelper {
    public function render() {
        return 'Hello World!';
    }

    public function __invoke()
    {
        return $this->render();
    }
}