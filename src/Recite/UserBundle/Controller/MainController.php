<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/")
 */
class MainController extends BaseController
{
    /**
     * @Route()
     * @Template()
     */
    public function indexAction()
    {

        $user = $this->getUser();

        return ['name' => $user ? $user->getUsername() : 'annoymous'];
    }
}
