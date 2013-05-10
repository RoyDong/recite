<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Controller\BaseController;
use Recite\MainBundle\Exception\ReciteException;


/**
 * @Route("/user")
 */
class UserController extends BaseController
{
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
                'roles' => $user->getRoles()
            ]);
        }

        throw new ReciteException(ReciteException::USER_NOT_FOUND, 
                'User not found or you have not login');
    }
}
