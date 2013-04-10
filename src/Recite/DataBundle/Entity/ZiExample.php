<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZiExample
 *
 * @ORM\Table("zi_example")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\ZiExampleRepository")
 */
class ZiExample
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
     * @ORM\Column(name="content", type="string")
     */
    private $content;

    /**
     *
     * @ORM\Column(name="en", type="string")
     */
    private $en;

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
     * Set content
     *
     * @param string $content
     * @return ZiExample
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

    /**
     * Set en
     *
     * @param string $en
     * @return ZiExample
     */
    public function setEn($en)
    {
        $this->en = $en;
    
        return $this;
    }

    /**
     * Get en
     *
     * @return string 
     */
    public function getEn()
    {
        return $this->en;
    }

    /**
     * Set zi
     *
     * @param \Recite\DataBundle\Entity\Zi $zi
     * @return ZiExample
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
}