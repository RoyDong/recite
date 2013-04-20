<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\Book;
use Recite\DataBundle\Entity\UserLearnBook;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/lesson")
 */
class LessonController extends BaseController {

    /**
     * @Route("/list")
     */
    public function listAction(){
        $this->accessFilter(['ROLE_USER']);
        $user = $this->getUser();
        $learns = $user->getOpenedBooks();

        if($learns){
            foreach($learns as $learn){

            }
        }
    }
}