<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\Course;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/book")
 */
class BookController extends BaseController
{

    /**
     * @Route("/list")
     */
    public function listAction(){
        $this->accessFilter(['ROLE_USER']);
        $page = (int)$this->get('request')->get('page', 1);
        $pageSize = (int)$this->get('request')->get('page_size', 20);
        $books = $this->Book->findWithUser($this->getUser(), $page, $pageSize);

        return $this->renderJson($books);
    }

    /**
     * @Route("/{id}",requirements={"id" = "\d+"})
     */
    public function showAction($id){
        $this->accessFilter(['ROLE_USER']);
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        if($book){
            return $this->renderJson([
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'descrition' => $book->getDescription(),
                'level' => $book->getLevel(),
                'size' => $book->getZiCount()
            ]);
        }
    }

    /**
     * @Route("/{id}/purchase",requirements={"id" = "\d+"})
     */
    public function purchaseAction($id) {
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        if($user->getCourseByBook($book)){
            throw new HttpException(403, 'you have already purchased this book');
        }

        $course = (new Course)->setUser($user)
                ->setBook($book)
                ->setPurchaseAt(time());

        if($user->getCourses()->count() < 1){
            $course->setStatus(Course::STATUS_OPEN);
        }

        $this->em()->persist($course);

        return $this->renderJson([]);
    }

    /**
     * @Route("/{id}/open",requirements={"id" = "\d+"})
     */
    public function openAction($id){
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $course = $user->getCourseByBook($book);

        if($course){
            $course->setStatus(Course::STATUS_OPEN);
            return $this->renderJson([]);
        }

        throw new HttpException(403, 'you must purchase this book first');
    }

    /**
     * @Route("/{id}/close",requirements={"id" = "\d+"})
     */
    public function closeAction($id){
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $course = $user->getCourseByBook($book);

        if($course){
            $course->close();
            return $this->renderJson([]);
        }

        throw new HttpException(403, 'you must purchase this book first');
    }
}