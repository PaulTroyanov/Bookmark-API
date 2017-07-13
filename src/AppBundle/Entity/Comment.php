<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This entity represents Comment which we can assign to existing Bookmark
 *
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $bookmark_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ip;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * Many Comments have One Bookmark.
     * @ORM\ManyToOne(targetEntity="Bookmark", inversedBy="comments")
     * @ORM\JoinColumn(name="bookmark_id", referencedColumnName="id")
     */
    private $bookmark;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bookmarkId
     *
     * @param integer $bookmarkId
     *
     * @return Comment
     */
    public function setBookmarkId($bookmarkId)
    {
        $this->bookmark_id = $bookmarkId;

        return $this;
    }

    /**
     * Get bookmarkId
     *
     * @return integer
     */
    public function getBookmarkId()
    {
        return $this->bookmark_id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Comment
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set bookmark
     *
     * @param \AppBundle\Entity\Bookmark $bookmark
     *
     * @return Comment
     */
    public function setBookmark(\AppBundle\Entity\Bookmark $bookmark = null)
    {
        $this->bookmark = $bookmark;

        return $this;
    }

    /**
     * Get bookmark
     *
     * @return \AppBundle\Entity\Bookmark
     */
    public function getBookmark()
    {
        return $this->bookmark;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
