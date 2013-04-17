<?php

namespace Recite\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Recite\DataBundle\Controller\BaseController;
use Recite\DataBundle\Entity\Book;
use Recite\DataBundle\Entity\UserLearnBook;

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

        return $this->renderJson([
            'error' => 'book not found'
        ]);
    }

    /**
     * @Route("/{id}/buy")
     */
    public function addAction($id)
    {
        $book = $this->Book->findOne($id);
        
        $user = $this->getUser();

        return $this->renderJson([
            'error' => 'book not found'
        ]);
    }
}
