<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Entity\User;

/**
 * @Route("/registration")
 * @Template()
 */
class RegistrationController extends BaseController
{
    /**
     * @Route("/register")
     */
    public function registerAction()
    {
        $request = $this->get('request');
        $email = $request->get('email');
        $user = $this->User->findOneByEmail($request->get('email'));

        if($user){
            return $this->renderJson(['error' => 'Email is used']);
        }

        $user = (new User)->setEmail($email);
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($request->get('password'), $user->getSalt()));

        return $this->renderJson(['name' => '董炜<>"&\'']);
    }

    private function login(){

    }

    private function rememberMe(){

    }
}