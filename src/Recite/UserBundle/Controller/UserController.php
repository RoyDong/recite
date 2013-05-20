<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Recite\DataBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;
use Recite\DataBundle\Controller\BaseController;
use Recite\MainBundle\Exception\ReciteException;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Route("/user")
 */
class UserController extends BaseController
{
    /**
     * @Route("/signup")
     */
    public function SignupAction()
    {
        $request = $this->get('request');
        $email = $request->get('email');
        $passwd = $request->get('password');

        if(strlen($passwd) < 6){
            throw new ReciteException(ReciteException::PASSWORD_TOO_SHORT, 
                    'Password must more than 6 chars');
        }

        if($this->get('validator')->validateValue($email, new Email)->count()){
            throw new ReciteException(
                    ReciteException::EMAIL_FORMAT_ERROR, 'Email format error');
        }

        if($this->User->isEmailExists($request->get('email'))){
            throw new ReciteException(
                    ReciteException::EMAIL_IS_USED, 'Email is used');
        }

        $role = $this->Role->findOneByName('ROLE_USER');
        $name = preg_replace('/@.*$/', '', $email);
        $user = (new User)->setEmail($email)->setUsername($name)->addRole($role);
        $this->em()->persist($user);
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($passwd, $user->getSalt()));
        $this->em()->flush();
        $this->signin($user);
        $response = $this->renderJson(['id' => $user->getId()]);

        if($request->get('remember_me')){
            $this->rememberMe($response);
        }

        return $response;
    }

    private function signin($user){
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


    /**
     * @Route("/current")
     */
    public function currentAction(){
        $this->getRequest()->getSession()->get(0);
        $user = $this->getUser();

        if($user){
            return $this->renderJson([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getUsername(),
                'role' => $user->getTopRole()
            ]);
        }

        throw new ReciteException(ReciteException::USER_NOT_SIGNIN, 
                'User not login');
    }

    /**
     * @Route(defaults={"id"=0})
     * @Route("/{id}", requirements={"id"="\d+"})
     */
    public function showAction($id)
    {
        $user = $id ? $this->User->find($id) : $this->getUser();

        if($user){
            return $this->renderJson([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getUsername(),
                'role' => $user->getTopRole()
            ]);
        }

        throw new ReciteException(ReciteException::USER_NOT_FOUND, 
                'User not found or you have not login');
    }
}