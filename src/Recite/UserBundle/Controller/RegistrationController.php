<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/registration")
 * @Template()
 */
class RegistrationController extends BaseController
{
    /**
     * @Route("/account")
     * @Method({"post"})
     */
    public function accountAction()
    {
        $user = $this->getUser();

        return ['name' => $user ? $user->getUsername() : 'annoymous'];
    }
}