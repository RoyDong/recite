<?php

namespace Recite\MainBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Recite\DataBundle\Controller\BaseController;

class ActionListener {

    protected $controller;

    protected $format;

    public function __construct(BaseController $baseController, $container){
        $baseController->setContainer($container);
        $this->controller = $baseController;
        $this->format = $baseController->getRequest()
                ->headers->get('Response-Format');
    }

    public function onKernelController(FilterControllerEvent $event){

    }

    public function onKernelResponse(FilterResponseEvent $event){
        $this->controller->em()->flush();
    }

    public function onKernelException(GetResponseForExceptionEvent $event){
        if($this->format === 'json'){
            $exception = $event->getException();
            $code = $exception->getCode();
            $event->setResponse($this->controller->renderJson(
                    null, $exception->getMessage(), $code ?: 1));
        }
    }
}