<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;
use Recite\DataBundle\Controller\BaseController;

/**
 * @Route("/registration")
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

        if($this->User->isEmailExists($request->get('email'))){
            return $this->renderJson(['error' => 'Email is used']);
        }

        $role = $this->Role->findOneByName('ROLE_USER');
        $user = (new User)->setEmail($email)->setUsername($email)->addRole($role);
        $this->em()->persist($user);
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($request->get('password'), $user->getSalt()));

        $this->em()->flush();
        $this->login($user);
        $response = $this->renderJson(['id' => $user->getId()]);

        if($request->get('remember_me')){
            $this->rememberMe($response);
        }

        return $response;
    }

    private function login($user){
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.context')->setToken($token);

        return $token;
    }

    private function rememberMe($response){
        $rememberMeService = new TokenBasedRememberMeServices(
                [$this->User], $this->container->getParameter('secret'),
                'main', [
                    'path' => '/',
                    'domain' => null,
                    'name' => 'REMEMBERME',
                    'lifetime' => 31536000,
                    'secure' => false,
                    'httponly' => true,
                    'always_remember_me' => true
                ]);

        $rememberMeService->loginSuccess($this->get('request'), $response,
                $this->get('security.context')->getToken());
    }
}