<?php

namespace Recite\MainBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ActionListener 
{

    public function onKernelController(FilterControllerEvent $event){

    }

    public function onKernelResponse(FilterResponseEvent $event){

    }

    public function onKernelException(GetResponseForExceptionEvent $event){
        $response = new Response($event->getException()->getMessage());

        $event->setResponse($response);
    }
}