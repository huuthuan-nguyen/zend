<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment {

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
     * @ORM\Column(name="text")
     */
    protected $text;

    public function setText($text) {
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Blog\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    public function getPost() {
        return $this->post;
    }

    public function setPost($post) {
        $this->post = $post;
        $post->addComment($this);
    }
}