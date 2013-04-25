<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\Book;
use Recite\DataBundle\Entity\Course;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/course")
 */
class CourseController extends BaseController {

    private $groupSize = 5;

    /**
     * @Route("/lessons")
     */
    public function lessonsAction(){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $courses = $user->getOpenedCourses();
        $json = [];

        foreach($courses as $course){
            $book = $course->getBook();
            $json[] = [
                'id' => $course->getId(),
                'book' => [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'description' => $book->getDescription(),
                    'ziCount' => $book->getZiCount()
                ],
                'classStatus' => $course->getClassStatus(),
                'context' => $course->getContext(),
                'pausedAt' => $course->getPausedAt()
            ];
        }

        return $this->renderJson($json);
    }

    /**
     * @Route("/{cid}/blackboard",requirements={"id" = "\d+"})
     */
    public function blackboardAction($cid){
        $course = $this->accessCourseFilter($cid);
        $context = $course->getContext();

        $zi = $this->getData($context);

        return $this->renderJson([
            'id' => $zi->getId(),
            'char' => $zi->getChar(),
            'action' => $context['action']
        ]);
    }

    /**
     * @Route("/{cid}/learn",requirements={"id" = "\d+"})
     */
    public function learnAction($cid){
        $course = $this->accessCourseFilter($cid);
        $context = $course->getContext();
    }

    public function accessCourseFilter($cid){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $course = $this->Course->find($cid);

        if($course && $course->isOpen() && $course->getUser() === $user){
            return $course;
        }

        throw new HttpException(404, 'course not found or is not open');
    }

    private function getData($context){
        $vIndex = $context['vIndex'];
        $tIndex = $context['tIndex'];
        $action = $context['action'];
        $count = count($context['results']);

        if($vIndex > $tIndex){
            $gap = $vIndex - $tIndex;
        }else{
            $gap = $count - $tIndex + $vIndex;
        }

        if($action === Course::ACTION_VIEW){
            $zid = $context['results'][$vIndex]['id'];
        }else if($action === Course::ACTION_TEST){
            $zid = $context['results'][$vIndex]['id'];
        }

        $zi = $this->Zi->find($zid);

        return $zi;
    }
}