<?php

namespace Recite\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @ORM\Table("course")
 * @ORM\Entity(repositoryClass="Recite\DataBundle\Repository\CourseRepository")
 * 
 */
class Course
{
    const STATUS_CLOSE = 0;
    const STATUS_OPEN = 1;

    const CLASS_STATUS_OPEN = 0;
    const CLASS_STATUS_MAIN = 1;   //main lesson
    const CLASS_STATUS_MAIN_END = 2;   //main lesson
    const CLASS_STATUS_REVIEW = 3;
    const CLASS_STATUS_CLOSE = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courses")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="courses")
     * @ORM\JoinColumn(name="bid", referencedColumnName="id")
     */
    private $book;

    /**
     * 
     * 
     * @ORM\Column(name="results", type="array")
     */
    private $results;

    /**
     * 
     * @ORM\Column(name="content", type="array")
     */
    private $content;

    /**
     * lesson number
     *
     * @ORM\Column(name="lesson_no",type="smallint")
     */
    private $lessonNo = 0;

    /**
     * 上一节课开始的时间
     *
     * @ORM\Column(name="lesson_begin_at",type="integer")
     */
    private $beginAt = 0;

    /**
     * 上一节课开始的时间
     *
     * @ORM\Column(name="lesson_end_time",type="integer")
     */
    private $endAt = 0;

    /**
     *
     * @ORM\Column(name="review_begin_at",type="integer")
     */
    private $reviewBeginAt = 0;

    /**
     *
     * @ORM\Column(name="review_end_at",type="integer")
     */
    private $reviewEndAt = 0;

    /**
     *
     * @ORM\Column(name="paused_at",type="integer")
     */
    private $pausedAt = 0;

    /**
     * 记录当前课花费的时间
     *
     * @ORM\Column(name="class_time",type="smallint")
     */
    private $classTime = 0;

    /**
     * 记录当前课花费的时间
     *
     * @ORM\Column(name="purchase_at",type="integer")
     */
    private $purchaseAt = 0;

    /**
     *
     * @ORM\Column(name="status",type="smallint")
     */
    private $status = 0;


    private $newZiLimit = 20;

    private $ziLimit = 40;

    private $maxLevel = 6;

    /**
     * 3:00 as the day day division timeline
     * 
     * @return int
     */
    public static function dayDivisionTime($time = null){
        return strtotime(date('Y-m-d', $time ?: time())) + 10800;
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
     * Set user
     *
     * @param \Recite\DataBundle\Entity\User $user
     * @return Course
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
     * @return Course
     */
    public function setBook(\Recite\DataBundle\Entity\Book $book = null)
    {
        $this->book = $book;
        $this->results = [];

        foreach($book->getZis() as $zi){
            /**
             * l for level
             * s for score
             * e for error 学习时出现错误的次数
             * t for time  下次出现时间点
             */
            $this->results[$zi->getId()] = [
                'l' => 0, 's' => 0, 'e' => 0, 't' => 0, ];
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

    /**
     * Get results
     *
     * @return array 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set lessonNo
     *
     * @param integer $lessonNo
     * @return Course
     */
    public function setLessonNo($lessonNo)
    {
        $this->lessonNo = $lessonNo;
    
        return $this;
    }

    /**
     * Get lessonNo
     *
     * @return integer 
     */
    public function getLessonNo()
    {
        return $this->lessonNo;
    }

    /**
     * Set lessonBeginAt
     *
     * @param integer $lessonBeginAt
     * @return Course
     */
    public function setBeginAt($beginAt)
    {
        $this->beginAt = $beginAt;
    
        return $this;
    }

    /**
     * Get lessonBeginAt
     *
     * @return integer 
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * Set endAt
     *
     * @param integer $endAt
     * @return Course
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    
        return $this;
    }

    /**
     * Get lessonEndAt
     *
     * @return integer 
     */
    public function getLessonEndAt()
    {
        return $this->lessonEndAt;
    }

    /**
     * Set reviewBeginAt
     *
     * @param integer $reviewBeginAt
     * @return Course
     */
    public function setReviewBeginAt($reviewBeginAt)
    {
        $this->reviewBeginAt = $reviewBeginAt;
    
        return $this;
    }

    /**
     * Get reviewBeginAt
     *
     * @return integer 
     */
    public function getReviewBeginAt()
    {
        return $this->reviewBeginAt;
    }

    /**
     * Set reviewEndAt
     *
     * @param integer $reviewEndAt
     * @return Course
     */
    public function setReviewEndAt($reviewEndAt)
    {
        $this->reviewEndAt = $reviewEndAt;
    
        return $this;
    }

    /**
     * Get reviewEndAt
     *
     * @return integer 
     */
    public function getReviewEndAt()
    {
        return $this->reviewEndAt;
    }

    /**
     * Set pausedAt
     *
     * @param integer $pausedAt
     * @return Course
     */
    public function setPausedAt($pausedAt)
    {
        $this->pausedAt = $pausedAt;
    
        return $this;
    }

    /**
     * Get pausedAt
     *
     * @return integer 
     */
    public function getPausedAt()
    {
        return $this->pausedAt;
    }

    /**
     * Get classTime
     *
     * @return integer 
     */
    public function getClassTime()
    {
        return $this->classTime;
    }

    /**
     * Set purchaseAt
     *
     * @param integer $purchaseAt
     * @return Course
     */
    public function setPurchaseAt($purchaseAt)
    {
        $this->purchaseAt = $purchaseAt;
    
        return $this;
    }

    /**
     * Get purchaseAt
     *
     * @return integer 
     */
    public function getPurchaseAt()
    {
        return $this->purchaseAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Course
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set results
     *
     * @param array $results
     * @return Course
     */
    public function setResults($results)
    {
        $this->results = $results;
    
        return $this;
    }

    /**
     * the status here do not contains the pausing info, see Course::isPaused()
     * 
     * @return type
     */
    public function getClassStatus(){
        $time = Course::dayDivisionTime();

        if($this->reviewEndAt > $time){
            return Course::CLASS_STATUS_CLOSE;
        }

        if($this->reviewBeginAt > $time){
            return Course::CLASS_STATUS_REVIEW;
        }
        
        if($this->endAt > $time){
            return Course::CLASS_STATUS_MAIN_END;
        }

        if($this->beginAt > $time){
            return Course::CLASS_STATUS_MAIN;
        }

        return Course::CLASS_STATUS_OPEN;
    }

    public function getContent(){
        $classStatus = $this->getClassStatus();        

        if($classStatus === Course::CLASS_STATUS_OPEN){
            $this->initContent();
        }

        if($classStatus === Course::CLASS_STATUS_MAIN_END){
            $this->initReviewContent();
        }

        if($classStatus === Course::CLASS_STATUS_CLOSE){
            return null;
        }

        return $this->content;
    }

    private function initContent(){
        $divisionTime = Course::dayDivisionTime();
        $reviewList = [];
        $ziCount = 0;

        foreach($this->results as $id => $result){
            if($result['l'] > 0 && $result['l'] < $this->maxLevel && 
                    $ziCount < 40 && $result['t'] < $divisionTime){

                $reviewList[$id] = $result;
                $ziCount++;
            }
        }

        $newList = [];
        $newZiCount = 0;

        if($ziCount < 40){
            foreach($this->results as $id => $result){
                if($result['l'] === 0 && $newZiCount < 20 && $ziCount < 40){
                    $newList[$id] = $result;
                    $newZiCount++;
                    $ziCount++;
                }
            }
        }

        $this->content = [
            'type' => Course::CLASS_STATUS_MAIN,
            'review' => $reviewList,
            'new' => $newList,
        ];
    }

    private function initReviewContent(){
        $newList = [];
        $newZiCount = 0;

        foreach($this->results as $id => $result){
            if($result['l'] === 0 && $newZiCount < 20){
                $newList[$id] = $result;
                $newZiCount++;
            }
        }

        $this->content = [
            'type' => Course::CLASS_STATUS_REVIEW,
            'new' => $newList,
        ];
    }
}