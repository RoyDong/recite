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

    /**
     * @Route("/{cid}",requirements={"cid"="\d+"})
     */
    public function showAction($cid){
        $course = $this->accessCourseFilter($cid);
        $context = $course->getContext();
        $result = $course->getCurrentResult();
        $zi = $this->Zi->find($result['id']);

        return $this->renderJson([
            'id' => $zi->getId(),
            'char' => $zi->getChar(),
            'results' => $course->getResults(),
            'vIndex' => $context['vIndex'],
            'tIndex' => $context['tIndex'],
            'action' => $context['action'],
            'count' => count($context['results']),
            'status' => $course->getClassStatus()
        ]);
    }

    /**
     * @Route("/lessons")
     */
    public function lessonsAction(){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $courses = $user->getOpenedCourses();
        $response = [];

        foreach($courses as $course){
            $book = $course->getBook();
            $response[] = [
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

        return $this->renderJson($response);
    }

    /**
     * @Route("/{cid}/blackboard",requirements={"cid" = "\d+"})
     */
    public function blackboardAction($cid){
        $course = $this->accessCourseFilter($cid);
        $context = $course->getContext();
        $result = $course->getCurrentResult();
        $zi = $this->Zi->find($result['id']);

        return $this->renderJson([
            'id' => $zi->getId(),
            'char' => $zi->getChar(),
            'vIndex' => $context['vIndex'],
            'tIndex' => $context['tIndex'],
            'action' => $context['action'],
            'count' => count($context['results']),
            'status' => $course->getClassStatus()
        ]);
    }

    /**
     * @Route("/{cid}/learn",requirements={"cid" = "\d+"})
     */
    public function learnAction($cid){
        $answer = (int)$this->get('request')->get('answer', 0);
        $course = $this->accessCourseFilter($cid);

        if($course->isClassAvailable()){
            $course->updateResult($answer);
            return $this->renderJson(1);
        }
        
        throw new HttpException(403, 'you cant learn this time');
    }

    private function accessCourseFilter($cid){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $course = $this->Course->find($cid);

        if($course && $course->isOpen() && $course->getUser() === $user){
            return $course;
        }

        throw new HttpException(404, 'course not found or is not open');
    }
}