<?php

namespace Recite\MainBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class ActionListener 
{
    private $security;

    private $em;

    public function __construct(SecurityContext $security, \Doctrine\ORM\EntityManager $em){
        $this->security = $security;
        $this->em = $em;
    }


    public function onKernelController(FilterControllerEvent $event){

    }

    public function onKernelResponse(FilterResponseEvent $event){
        $this->em->flush();
    }

    public function onKernelException(GetResponseForExceptionEvent $event){
        $response = new Response($event->getException()->getMessage());

        $event->setResponse($response);
    }
}