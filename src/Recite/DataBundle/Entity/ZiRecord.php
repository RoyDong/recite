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
     * ā á ǎ à ē é ě è ī í ǐ ì ō ó ǒ ò ū ú ǔ ù ǖ ǘ ǚ ǜ ü ê ɑ  ń ń ň 
     * 
     * 
     * @var array
     */
    private static $pinyins = [
        'a1' => 'ā',
        'a2' => 'á',
        'a3' => 'ǎ',
        'a4' => 'à',
        'e1' => 'ē',
        'e2' => 'é',
        'e3' => 'ě',
        'e4' => 'è',
        'i1' => 'ī',
        'i2' => 'í',
        'i3' => 'ǐ',
        'i4' => 'ì',
        'o1' => 'ō',
        'o2' => 'ó',
        'o3' => 'ǒ',
        'o4' => 'ò',
        'v1' => 'ū',
        'v2' => 'ú',
        'v3' => 'ǔ',
        'v4' => 'ù',
    ];

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
     * @ORM\Column(name="zh", type="string")
     */
    private $zh;

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
        return str_replace(array_keys(self::$pinyins), self::$pinyins, $this->pinyin);
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
}