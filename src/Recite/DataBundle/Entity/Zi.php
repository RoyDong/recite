<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zi
 *
 * @ORM\Table("zi")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\ZiRepository")
 */
class Zi
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
     *
     * @ORM\Column(name="`char`", type="string", length=1, unique=true)
     */
    private $char;

    /**
     *
     * @ORM\Column(name="radical", type="string", length=1)
     */
    private $radical;

    /**
     *
     * @ORM\Column(name="radical_stroke_count", type="smallint")
     */
    private $radicalStrokeCount;

    /**
     *
     * @ORM\Column(name="stroke_count", type="smallint")
     */
    private $strokeCount;

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
     * Set char
     *
     * @param string $char
     * @return Zi
     */
    public function setChar($char)
    {
        $this->char = $char;
    
        return $this;
    }

    /**
     * Get char
     *
     * @return string 
     */
    public function getChar()
    {
        return $this->char;
    }

    /**
     * Set radical
     *
     * @param string $radical
     * @return Zi
     */
    public function setRadical($radical)
    {
        $this->radical = $radical;
    
        return $this;
    }

    /**
     * Get radical
     *
     * @return string 
     */
    public function getRadical()
    {
        return $this->radical;
    }

    /**
     * Set radicalStrokeCount
     *
     * @param int $radicalStrokeCount
     * @return Zi
     */
    public function setRadicalStrokeCount($radicalStrokeCount)
    {
        $this->radicalStrokeCount = $radicalStrokeCount;
    
        return $this;
    }

    /**
     * Get radicalStrokeCount
     *
     * @return int
     */
    public function getRadicalStrokeCount()
    {
        return $this->radicalStrokeCount;
    }

    /**
     * Set strokeCount
     *
     * @param int $strokeCount
     * @return Zi
     */
    public function setStrokeCount($strokeCount)
    {
        $this->strokeCount = $strokeCount;
    
        return $this;
    }

    /**
     * Get strokeCount
     *
     * @return int 
     */
    public function getStrokeCount()
    {
        return $this->strokeCount;
    }
}