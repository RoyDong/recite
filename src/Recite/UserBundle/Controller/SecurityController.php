<?php

namespace Recite\UserBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/security")
 */
class SecurityController extends BaseController
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'ReciteUserBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_name' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction(){
        $this->forward();
    }
}