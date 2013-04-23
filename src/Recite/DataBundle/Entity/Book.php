<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Book
 *
 *
 * @ORM\Table("book")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\BookRepository")
 */
class Book
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
     * @ORM\Column(name="title", type="string", length=60, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="zi_count", type="smallint")
     */
    private $ziCount;

    /**
     * @ORM\ManyToMany(targetEntity="Zi", inversedBy="books")
     * @ORM\JoinTable(name="book_zi")
     */
    private $zis;

    /**
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="book")
     */
    private $courses;


    public function __construct() {
        $this->zis = new ArrayCollection;
        $this->courses = new ArrayCollection;
    }

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
     * Set title
     *
     * @param string $title
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add zis
     *
     * @param \Recite\DataBundle\Entity\Zi $zis
     * @return Book
     */
    public function addZi(\Recite\DataBundle\Entity\Zi $zis)
    {
        $this->zis[] = $zis;
    
        return $this;
    }

    /**
     * Remove zis
     *
     * @param \Recite\DataBundle\Entity\Zi $zis
     */
    public function removeZi(\Recite\DataBundle\Entity\Zi $zis)
    {
        $this->zis->removeElement($zis);
    }

    /**
     * Get zis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getZis()
    {
        return $this->zis;
    }

    /**
     * Set ziCount
     *
     * @param integer $ziCount
     * @return Book
     */
    public function setZiCount($ziCount)
    {
        $this->ziCount = $ziCount;
    
        return $this;
    }

    /**
     * Get ziCount
     *
     * @return integer 
     */
    public function getZiCount()
    {
        return $this->ziCount;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Book
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Add courses
     *
     * @param \Recite\DataBundle\Entity\Course $courses
     * @return Book
     */
    public function addCourse(\Recite\DataBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \Recite\DataBundle\Entity\Course $courses
     */
    public function removeCourse(\Recite\DataBundle\Entity\Course $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}