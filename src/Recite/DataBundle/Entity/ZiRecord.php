<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table("zi_record")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\RecordRepository")
 */
class ZiRecord
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
     * @ORM\Column(name="zid", type="integer")
     */
    private $zid;

    /**
     *
     * @ORM\Column(name="pinyin", type="string", length=10)
     */
    private $pinyin;

    /**
     *
     * @ORM\Column(name="zh", type="string")
     */
    private $zh;

    /**
     *
     * @ORM\Column(name="en", type="string")
     */
    private $en;

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
     * Set zid
     *
     * @param integer $zid
     * @return ZiRecord
     */
    public function setZid($zid)
    {
        $this->zid = $zid;
    
        return $this;
    }

    /**
     * Get zid
     *
     * @return integer 
     */
    public function getZid()
    {
        return $this->zid;
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

    /**
     * Set zh
     *
     * @param string $zh
     * @return ZiRecord
     */
    public function setZh($zh)
    {
        $this->zh = $zh;
    
        return $this;
    }

    /**
     * Get zh
     *
     * @return string 
     */
    public function getZh()
    {
        return $this->zh;
    }

    /**
     * Set en
     *
     * @param string $en
     * @return ZiRecord
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
}