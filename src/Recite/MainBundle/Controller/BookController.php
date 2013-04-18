<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\Book;
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
        $book = $this->Book->findOne($id);

        if($book){
            return $this->renderJson([
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'descrition' => $book->getDescription(),
                'ziCount' => $book->getZiCount()
            ]);
        }

        throw new HttpException(404, 'book not found');
    }

    /**
     * @Route("/{id}/buy")
     */
    public function addAction($id)
    {
        $this->accessFilter(['ROLE_USER'], 'post');
        $user = $this->getUser();
        $book = $this->Book->findOne($id);

        if(!$book){
            throw new HttpException(404, 'book not found');
        }

        $learnBook = (new UserLearnBook)->setUser($user)->setBook($book);
        $this->em()->persist($learnBook);
        $this->em()->flush();

        return $this->renderJson(['id' => $learnBook->getId()]);
    }
}
