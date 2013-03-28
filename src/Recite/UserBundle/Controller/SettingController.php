<?php

namespace Recite\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Recite\DataBundle\Controller\BaseController;


/**
 * @Route("/")
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

        $a = $this->User->findOneByEmail('g@zuo.com');

        ldd($a);

        return [
            'name' => $user ? $user->getUsername() : 'annoymous',
            'roles' => $user ? $user->getRoles() : []
        ];
    }
}
