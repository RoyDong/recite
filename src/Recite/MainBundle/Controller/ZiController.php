<?php

namespace Recite\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Controller\BaseController;

/**
 * @Route("/")
 */
class ZiController extends BaseController
{
    /**
     * @Route("/{char}")
     */
    public function basicAction($char)
    {
        $zi = $this->Zi->findOneByChar($char); 

        if(is_null($zi)){
            return $this->renderJson(['error' => 'not found :'.$char]);
        }

        $zhs = [];

        foreach($zi->getZhs() as $zh){
            $zhs[] = [
                'id' => $zh->getId(),
                'pinyin' => $zh->getPinyin(),
                'zhPinyin' => $zh->getZhPinyin(),
                'content' => $zh->getContent()
            ];
        }

        return $this->renderJson(['id' => $zi->getId(), 
                'char' => $zi->getChar(), 'zhs' => $zhs]);
    }
}
