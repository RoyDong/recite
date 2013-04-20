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
    const STATUS_CLOSED = 0;
    const STATUS_OPENED = 1;

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
     * results 字段保存了用户学习这本书的详细进度，记录了他当前对于每一个字的熟练程度(level)，
     * 学习了几轮，以及出错了几次等等信息
     * 
     * 字的初始level为0，当字的level到了6级或这以上则表示这个字已经被用户掌握，学习完成
     * 
     * 学习按照课时（lesson）进行，每天最多有两个课时（可能没有），每节课不应该超过半个小时
     * 每节课不能暂停超过20分钟，否则这节课的学习结果将作废，需要重新开始
     * 
     * 
     * 根据begin end时间判断当前的学习状态（lesson或review或都没开始或都已结束，
     *       lesson没开始，review一定没开始，review结束了lesson一定结束）
     * 
     * 
     * lesson选择每次要学习的字和分组：根据lesson和字level来选取这节课需要学习的字：
     *     1.第一次学习这本书（last_lesson_no为0）依次取出20字
     *     2.取出40个level大于0并且学习时间（time）已到的字，如果不足40个则取出20个level为
     *       0的字,取完为止
     * 
     * review选择level为1的单词进行学习，最多20个
     * 
     * 把字按五个一组划分，最后一组允许不足五个，学习过程如下： 
     *     1.依次显示第n组每个字的解释，让用户浏览（view）。如果n组不存在则进入5步
     *     2.然后开始测试（test）第n-1组（上一组）的五个字，按浏览次序依次测试，如果上一组不存在则略过2，3步
     *     3.测试结果（result）列出这次test的字，标记出哪些字在本次test中通过，哪些没有还需要继续学习
     *     4.n=n+1 循环进入第1步
     *     5.一轮（round）完成 
     * 
     * view每个字时用户有两个选择:
     *     1.不明白。选择此项表明用户不认识或者不熟悉这个字，该字的score=0
     *     2.我会了。选择此项表明用户熟知这个字，该字的score=1
     * 
     * test每个字时用户从一些（4个左右）选项中选择出唯一一个正确的解释：
     *     选择正确：该字的score+1
     *     选择错误：该字的score=0
     * 计算出得分后：
     *     1.字的level=0,score=2表明用户（极有可能）第一次学习该字之前就已经熟知该字，
     *       字的level直接变为6
     *     2.score > 0，通过测试，level = level + score
     *     3.score = 0，没有通过测试，level不变, error + 1
     *     
     * result:得分大于0的字为学习通过，否则需要继续学习。学习通过的字根据level计算出下次学习
     * 的时间（time）。level所对应的间隔时间：
     *     1 => 3h, 2 => 1d, 3 => 2d, 4 => 7d, 5 => 30d
     * 
     * 每节课取出的所有字都通过则该课时完成，如果学习时间（class_time）超过40分钟则强制结束，
     * 并保存学习结果，没有通过的单词下节课学习
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
    private $lessonBeginAt = 0;

    /**
     * 上一节课开始的时间
     *
     * @ORM\Column(name="lesson_end_time",type="integer")
     */
    private $lessonEndAt = 0;

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

    /**
     * 3:00 as the day divid timeline
     * 
     * @return int
     */
    public static function dayDividTimeline(){
        return strtotime(date('Y-m-d')) + 10800;
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
     * @return UserLearnBook
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
     * @return UserLearnBook
     */
    public function setLessonBeginAt($lessonBeginAt)
    {
        $this->lessonBeginAt = $lessonBeginAt;
    
        return $this;
    }

    /**
     * Get lessonBeginAt
     *
     * @return integer 
     */
    public function getLessonBeginAt()
    {
        return $this->lessonBeginAt;
    }

    /**
     * Set lessonEndAt
     *
     * @param integer $lessonEndAt
     * @return UserLearnBook
     */
    public function setLessonEndAt($lessonEndAt)
    {
        $this->lessonEndAt = $lessonEndAt;
    
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
     * @return UserLearnBook
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
     * @return UserLearnBook
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
     * @return UserLearnBook
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
     * Set classTime
     *
     * @param integer $classTime
     * @return UserLearnBook
     */
    public function setClassTime($classTime)
    {
        $this->classTime = $classTime;
    
        return $this;
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
     * @return UserLearnBook
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
     * @return UserLearnBook
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
     *
     * @return array
     */
    public function getContent(){
        return $this->content;
    }

    public function getLessonContent(){
        $content = [];

        foreach($this->results as $zid => $result){
            if($result['time']){

            }
            $content[$zid] = $result;
        }
    }

    public function getReviewContent(){
        foreach($this->results as $zid => $result){
            
        }
    }

    /**
     * 
     * @return string main|review or null
     */
    public function getClassType(){
        $timeline = self::dayDividTimeline();

        if($this->reviewEndAt >= $timeline){
            return null;
        }

        if($this->reviewBeginAt >= $timeline){
            return 'review';
        }

        return 'main';
    }

    public function getClassStatus(){

    }
}