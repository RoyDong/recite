<?php

namespace Recite\UserBundle\Listener;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Recite\DataBundle\Controller\BaseController;
use Recite\MainBundle\Exception\ReciteException;

class AuthenticationListener implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface
{

    protected $controller;

    protected $format;

    public function __construct(BaseController $baseController, $container) {
        $baseController->setContainer($container);
        $this->controller = $baseController;
        $this->format = $baseController->getRequest()
                ->headers->get('Response-Format');
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @see \Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener
     * @param Request        $request
     * @param TokenInterface $token
     * @return Response the response to return
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        if($this->format === 'json'){
            return $this->controller->renderJson();
        }
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception    
     * @return Response the response to return
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        if($this->format === 'json'){
            return $this->controller->renderJson(
                    null, $exception->getMessage(), ReciteException::BAD_CREDENTIAL);
        }
    }

    public function onLogoutSuccess(Request $request) {
        if($this->format === 'json'){
            return $this->controller->renderJson();
        }
    }
}