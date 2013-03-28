<?php

namespace Recite\DictBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Controller\BaseController;

/**
 * @Route("/")
 */
class ZiController extends BaseController
{
    /**
     * @Route("/{char}")
     */
    public function indexAction($char)
    {
        $zi = $this->Zi->findOneByChar($char); 

        if(is_null($zi)){
            return $this->renderJson(['error' => 'not found :'.$char]);
        }

        $records = [];

        foreach($zi->getRecords() as $record){
            $records[] = [
                'id' => $record->getId(),
                'pinyin' => $record->getPinyin(),
                'zhPinyin' => $record->getZhPinyin(),
                'zh' => $record->getZh()
            ];
        }

        return $this->renderJson(['id' => $zi->getId(), 
                'char' => $zi->getChar(), 'records' => $records]);
    }
}
