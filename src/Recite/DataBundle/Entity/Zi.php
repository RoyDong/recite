<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Zi
 *
 * @ORM\Table("zi")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\ZiRepository")
 */
class Zi
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

        'u1' => 'ū',
        'u2' => 'ú',
        'u3' => 'ǔ',
        'u4' => 'ù',

        'v1' => 'ǖ',
        'v2' => 'ǘ',
        'v3' => 'ǚ',
        'v4' => 'ǜ',
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
     * @ORM\OneToMany(targetEntity="ZiZh", mappedBy="zi")
     */
    private $zhs;

    /**
     * @ORM\OneToMany(targetEntity="ZiEn", mappedBy="zi")
     */
    private $ens;

    /**
     * @ORM\OneToMany(targetEntity="ZiExample", mappedBy="zi")
     */
    private $examples;

    public function __construct() {
        $this->zhs = new ArrayCollection;
        $this->ens = new ArrayCollection;
        $this->examples = new ArrayCollection;
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

    /**
     * Add zhs
     *
     * @param \Recite\DataBundle\Entity\ZiZh $zhs
     * @return Zi
     */
    public function addZh(\Recite\DataBundle\Entity\ZiZh $zhs)
    {
        $this->zhs[] = $zhs;
    
        return $this;
    }

    /**
     * Remove zhs
     *
     * @param \Recite\DataBundle\Entity\ZiZh $zhs
     */
    public function removeZh(\Recite\DataBundle\Entity\ZiZh $zhs)
    {
        $this->zhs->removeElement($zhs);
    }

    /**
     * Get zhs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getZhs()
    {
        return $this->zhs;
    }

    /**
     * Add ens
     *
     * @param \Recite\DataBundle\Entity\ZiEn $ens
     * @return Zi
     */
    public function addEn(\Recite\DataBundle\Entity\ZiEn $ens)
    {
        $this->ens[] = $ens;
    
        return $this;
    }

    /**
     * Remove ens
     *
     * @param \Recite\DataBundle\Entity\ZiEn $ens
     */
    public function removeEn(\Recite\DataBundle\Entity\ZiEn $ens)
    {
        $this->ens->removeElement($ens);
    }

    /**
     * Get ens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEns()
    {
        return $this->ens;
    }

    public static function pinyin($pinyin, $hasYindiao = true){
        $yindiao = (int)substr($pinyin, -1);
        $pinyin = substr($pinyin, 0, -1);

        if($yindiao > 0 && $hasYindiao){
            foreach(['a','e','i','o','u','v', false] as $yunmu){
                if(strpos($pinyin, $yunmu)) break;
            }

            if($yunmu){
                return str_replace($yunmu, 
                        self::$pinyins[$yunmu.$yindiao], $pinyin);
            }
        }

        return $pinyin;
    }

    /**
     * Add examples
     *
     * @param \Recite\DataBundle\Entity\ZiExample $examples
     * @return Zi
     */
    public function addExample(\Recite\DataBundle\Entity\ZiExample $examples)
    {
        $this->examples[] = $examples;
    
        return $this;
    }

    /**
     * Remove examples
     *
     * @param \Recite\DataBundle\Entity\ZiExample $examples
     */
    public function removeExample(\Recite\DataBundle\Entity\ZiExample $examples)
    {
        $this->examples->removeElement($examples);
    }

    /**
     * Get examples
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExamples()
    {
        return $this->examples;
    }
}