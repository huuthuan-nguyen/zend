<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId($id) {
        return $this->id;
    }

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @ORM\ManyToMany(targetEntity="\Blog\Entity\Post", mappedBy="tags")
     */
    protected $posts;

    public function getPosts() {
        return $this->posts;
    }

    public function addPost($post) {
        $this->posts[] = $post;
    }

    public function __construct() {
        $this->posts = new ArrayCollection();
    }
}