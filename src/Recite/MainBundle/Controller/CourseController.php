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
     * @Route("/lessons")
     */
    public function lessonsAction(){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $course = $user->getOpenedCourses();

        foreach($course as $course){
            ld($course);
        }

        return $this->renderJson();
    }
}