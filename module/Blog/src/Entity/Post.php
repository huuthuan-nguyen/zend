<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Blog\Entity\Comment;
use Blog\Entity\Tag;


/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class Post {

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
     * @ORM\Column(name="title")
     */
    protected $title;

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
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
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Comment", mappedBy="post")
     * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
     */
    protected $comments;

    public function __construct() {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function addComment($comment) {
        $this->comments[] = $comment;
    }

    /**
     * @ORM\ManyToMany(targetEntity="\Blog\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="post_tag",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")})
     *      )
     */
    protected $tags;

    public function getTags() {
        return $this->tags;
    }

    public function addTag($tag) {
        $this->tags[] = $tag;
    }

    public function removeTagAssociation($tag) {
        $this->tags->removeElement($tag);
    }
}