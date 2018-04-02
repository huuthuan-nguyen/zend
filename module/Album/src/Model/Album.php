<?php
namespace Album\Model;
/**
 * Created by PhpStorm.
 * User: patrick.thuan
 * Date: 4/2/2018
 * Time: 2:11 PM
 */

class Album {
    public $id;
    public $artist;
    public $title;

    public function exchangeArray(array $data) {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->artist = !empty($data['artist']) ? $data['artist'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
    }
}