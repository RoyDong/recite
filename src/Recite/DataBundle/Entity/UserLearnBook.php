<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLearnBook
 *
 * @ORM\Table("user_learn_book")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\UserLearnBookRepository")
 * 
 */
class UserLearnBook
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="learningBooks")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="learningUsers")
     * @ORM\JoinColumn(name="bid", referencedColumnName="id")
     */
    private $book;

    /**
     * @ORM\Column(name="skill", type="array")
     */
    private $skill;

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
     * Set user
     *
     * @param \Recite\DataBundle\Entity\User $user
     * @return UserLearnBook
     */
    public function setUser(\Recite\DataBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Recite\DataBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param \Recite\DataBundle\Entity\Book $book
     * @return UserLearnBook
     */
    public function setBook(\Recite\DataBundle\Entity\Book $book = null)
    {
        $this->book = $book;
        $this->skill = [];

        foreach($book->getZis() as $zi){
            /**
             * v for value
             * e for error
             * r for repeat
             */
            $this->skill[$zi->getId()] = ['v' => 0, 'e' => 0, 'r' => 0];
        }

        return $this;
    }

    /**
     * Get book
     *
     * @return \Recite\DataBundle\Entity\Book 
     */
    public function getBook()
    {
        return $this->book;
    }
}