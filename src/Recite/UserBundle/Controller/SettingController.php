<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Controller\BaseController;


/**
 * @Route("/setting")
 */
class SettingController extends BaseController
{
    /**
     * @Route()
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return [
            'name' => $user ? $user->getUsername() : 'annoymous',
            'roles' => $user ? $user->getRoles() : []
        ];
    }
}
