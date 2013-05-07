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

    const CLASS_STATUS_MAIN_OPEN = 0;
    const CLASS_STATUS_MAIN = 1;   //main lesson
    const CLASS_STATUS_MAIN_END = 2;
    const CLASS_STATUS_REVIEW_OPEN = 3;
    const CLASS_STATUS_REVIEW = 4;
    const CLASS_STATUS_CLOSE = 5;

    const ACTION_VIEW = 0;
    const ACTION_TEST = 1;
    const ACTION_REVIEW = 2;

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
     * @ORM\Column(name="context", type="array")
     */
    private $context;

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
     *
     * @ORM\Column(name="purchase_at",type="integer")
     */
    private $purchaseAt = 0;

    /**
     *
     * @ORM\Column(name="status",type="smallint")
     */
    private $status = 0;

    private $maxLevel = 6;

    private $ziLimit = 40;

    private $newZiLimit = 20;

    private $groupSize = 5;

    /**
     * 3:00 as the day day division timeline
     * 
     * @return int
     */
    public static function dayStartTime($time = null){
        return strtotime(date('Y-m-d', $time ?: time())) + 14400;
    }

    /**
     * 3:00 as the day day division timeline
     * 
     * @return int
     */
    public static function dayEndTime($time = null){
        return strtotime(date('Y-m-d', $time ?: time())) + 86400;
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
            $this->results[$zi->getId()] = [
                'level' => 0, 'error' => 0, 'repeat' => 0, 'time' => 0];
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

    public function close(){
        $this->status = Course::STATUS_CLOSE;
        $this->context = null;
        $this->beginAt = 0;
        $this->endAt = 0;
        $this->reviewBeginAt = 0;
        $this->reviewEndAt = 0;
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

    public function isOpen(){
        return $this->status === Course::STATUS_OPEN;
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
        $dayStart = Course::dayStartTime();
        $time = time();

        if($time < $dayStart && $this->reviewEndAt > $dayStart){
            return Course::CLASS_STATUS_CLOSE;
        }

        if($this->reviewBeginAt > $dayStart){
            return Course::CLASS_STATUS_REVIEW;
        }

        if($this->endAt > $dayStart){
            if($time < $this->endAt + 10800){
                return Course::CLASS_STATUS_MAIN_END;
            }

            return Course::CLASS_STATUS_REVIEW_OPEN;
        }

        if($this->beginAt > $dayStart){
            return Course::CLASS_STATUS_MAIN;
        }

        return Course::CLASS_STATUS_MAIN_OPEN;
    }

    public function isClassAvailable(){
        $status = $this->getClassStatus();

        return $status === Course::CLASS_STATUS_MAIN_OPEN ||
                $status === Course::CLASS_STATUS_MAIN ||
                $status === Course::CLASS_STATUS_REVIEW_OPEN ||
                $status === Course::CLASS_STATUS_REVIEW;
    }

    public function getContext(){
        $classStatus = $this->getClassStatus();

        if($classStatus === Course::CLASS_STATUS_CLOSE){
            return null;
        }

        if($classStatus === Course::CLASS_STATUS_MAIN_OPEN &&
                !$this->isContextInitialized()){

            $this->initContent();
        }

        return $this->context;
    }

    public function getResultByIndex($index){
        if(isset($this->context['results'][$index])){
            return $this->context['results'][$index];
        }

        return null;
    }

    public function getCurrentResult($action = null){
        if(is_null($action)){
            $action = $this->context['action'];
        }

        if($action === Course::ACTION_VIEW){
            return $this->context['results'][$this->context['vIndex']];
        }

        if($action === Course::ACTION_TEST){
            return $this->context['results'][$this->context['tIndex']];
        }
    }

    public function updateResult($answer){
        if($this->context['action'] === Course::ACTION_VIEW){
            $index = $this->context['vIndex'];
            $result = $this->getResultByIndex($index);     

            if($answer){
                $result['score']++;
            }

            $this->context['vIndex']++;
            $this->context['results'][$index] = $result;
        }
        else if($this->context['action'] === Course::ACTION_TEST){
            $index = $this->context['tIndex'];
            $result = $this->getResultByIndex($index);
            $result['repeat']++;
            
            if($answer){
                $result['score']++;

                if($result['level'] == 0 && $result['score'] >= 2){
                    $result['level'] = 6;
                }else{
                    $result['level'] += $result['score'];
                }

                $result['time'] = $this->getShowTime($result['level']);
                unset($result['score']);
                $this->context['passed'][] = $result;
                unset($this->context['results'][$index]);
                $count = count($this->context['results']);

                if($count == 0){
                    return $this->finishClass();
                }

                $this->context['results'] = array_values($this->context['results']);

                if($this->context['vIndex'] > $index){
                    $this->context['vIndex']--;
                }
            }
            else{
                $result['score'] = 0;
                $result['error']++;
                $this->context['results'][$index] = $result;
                $this->context['tIndex']++;
            }
        }

        $this->context['count']--;
        $this->updateIndexAndAction();
        $this->updateClassStatus();
    }

    private function updateClassStatus(){
        $classStatus = $this->getClassStatus();

        if($classStatus === Course::CLASS_STATUS_MAIN_OPEN){
            $this->beginAt = time();
        }
        else if($classStatus === Course::CLASS_STATUS_MAIN_END){
            $this->reviewBeginAt = time();
        }
    }

    private function updateIndexAndAction(){
        $count = count($this->context['results']);

        if($this->context['vIndex'] >= $count){
            $this->context['vIndex'] = 0;
        }

        if($this->context['tIndex'] >= $count){
            $this->context['tIndex'] = 0;
        }

        if($count > $this->groupSize){
            if($this->context['count'] <= 0){
                $this->context['action'] = 1 - $this->context['action'];
                $this->context['count'] = $this->groupSize;
            }
        }
        else if($this->context['action'] === Course::ACTION_VIEW &&
                $this->context['vIndex'] === $this->context['tIndex']){

            $this->context['action'] = Course::ACTION_TEST;
        }
    }

    public function getShowTime($level){
        switch($level){
            case 1:
                $hour = 0;
                break;
            case 2:
                $hour = 24;
                break;
            case 3:
                $hour = 48;
                break;
            case 4:
                $hour = 168;
                break;
            case 5:
                $hour = 720;
                break;
            default :
                return 0;
        }

        return Course::dayStartTime() + 3600 * $hour;
    }

    public function finishClass(){
        $classStatus = $this->getClassStatus();

        if($classStatus === Course::CLASS_STATUS_MAIN){
            $this->endAt = time();
        }
        else if($classStatus === Course::CLASS_STATUS_REVIEW){
            $this->reviewEndAt = time();
        }

        $review = [];
        $dayStart = Course::dayStartTime();

        foreach($this->context['passed'] as $result){
            $this->results[$result['id']] = [
                'level' => $result['level'],
                'error' => $result['error'],
                'time' => $result['time'],
            ];

            if($result['repeat'] === 1 && $result['time'] <= $dayStart){
                $review[] = $result;
            }
        }

        if($review){
            $this->context = [
                'type' => Course::CLASS_STATUS_REVIEW,
                'results' => $review,
                'passed' => [], 
                'time' => time(),
                'vIndex' => 0,
                'tIndex' => 0,
                'count' => 2 * $this->groupSize,
                'action' => Course::ACTION_VIEW
            ];
        }
        else{
            $this->context = null;
        }

        $this->lessonNo++;
    }

    private function isContextInitialized(){
        if(is_array($this->context) && 
                $this->context['time'] > Course::dayStartTime()){

            return true;
        }

        return false;
    }

    private function initContent(){
        $startTime = Course::dayStartTime();
        $results = [];
        $ziCount = 0;
        $newZiCount = 0;

        foreach($this->results as $id => $result){
            if($result['level'] > 0 && $result['level'] < $this->maxLevel && 
                    $ziCount < $this->ziLimit && $result['time'] <= $startTime){

                $result['score'] = 0;
                $result['id'] = $id;
                $results[] = $result;
                $ziCount++;
            }
        }

        if($ziCount < $this->ziLimit){
            foreach($this->results as $id => $result){
                if($result['level'] === 0 && $newZiCount < $this->newZiLimit && 
                        $ziCount < $this->ziLimit){

                    $result['score'] = 0;
                    $result['id'] = $id;
                    $results[] = $result;
                    $newZiCount++;
                    $ziCount++;
                }
            }
        }

        $this->context = [
            'type' => Course::CLASS_STATUS_MAIN,
            'results' => $results,
            'passed' => [], 
            'time' => time(),
            'vIndex' => 0,
            'tIndex' => 0,
            'count' => 2 * $this->groupSize,
            'action' => Course::ACTION_VIEW
        ];
    }
}