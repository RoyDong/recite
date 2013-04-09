<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table("zi_zh")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\ZiZhRepository")
 */
class ZiZh
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
     * @ORM\Column(name="pinyin", type="string", length=10)
     */
    private $pinyin;

    /**
     *
     * @ORM\Column(name="content", type="string")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Zi")
     * @ORM\JoinColumn(name="zid", referencedColumnName="id")
     */
    private $zi;

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
     * Set pinyin
     *
     * @param string $pinyin
     * @return ZiRecord
     */
    public function setPinyin($pinyin)
    {
        $this->pinyin = $pinyin;
    
        return $this;
    }

    /**
     * Get pinyin
     *
     * @return string 
     */
    public function getPinyin()
    {
        return $this->pinyin;
    }

    public function getZhPinyin(){
        return Zi::pinyin($this->pinyin);
    }


    /**
     * Set zi
     *
     * @param \Recite\DataBundle\Entity\Zi $zi
     * @return ZiRecord
     */
    public function setZi(\Recite\DataBundle\Entity\Zi $zi = null)
    {
        $this->zi = $zi;
    
        return $this;
    }

    /**
     * Get zi
     *
     * @return \Recite\DataBundle\Entity\Zi 
     */
    public function getZi()
    {
        return $this->zi;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return ZiZh
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
}