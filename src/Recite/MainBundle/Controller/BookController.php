<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\UserLearnBook;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/book")
 */
class BookController extends BaseController
{
    /**
     * @Route("/{id}")
     */
    public function showAction($id){
        $this->accessFilter(['ROLE_USER']);
        $book = $this->Book->findOne($id);
        $user = $this->getUser();

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $learn = $user->getLearningByBook($book);

        if($learn){
            ldd($learn);
        }

        if($book){
            return $this->renderJson([
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'descrition' => $book->getDescription(),
                'ziCount' => $book->getZiCount()
            ]);
        }
    }

    /**
     * @Route("/{id}/purchase")
     */
    public function purchaseAction($id) {
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        if($user->getLearningByBook($book)){
            throw new HttpException(403, 'you have already purchased this book');
        }

        $learn = (new UserLearnBook)->setUser($user)
                ->setBook($book)
                ->setPurchaseAt(time());

        if($user->getLearningBooks()->count() < 1){
            $learn->setStatus(UserLearnBook::STATUS_OPENED);
        }

        $this->em()->persist($learn);

        return $this->renderJson([]);
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction($id){
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $learn = $user->getLearningByBook($book);

        if($learn){
            $learn->setStatus(UserLearnBook::STATUS_OPENED);
            return $this->renderJson([]);
        }

        throw new HttpException(403, 'you must purchase this book first');
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction($id){
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $learn = $user->getLearningByBook($book);

        if($learn){
            $learn->setStatus(UserLearnBook::STATUS_CLOSED);
            return $this->renderJson([]);
        }

        throw new HttpException(403, 'you must purchase this book first');
    }
}