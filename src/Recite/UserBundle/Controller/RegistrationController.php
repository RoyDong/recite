<?php

namespace Recite\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/registration")
 * @Template()
 */
class RegistrationController extends Controller
{
    /**
     * @Route()
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');

        if($request->isMethod('post')){

        }

        $user = $this->getUser();

        return ['name' => $user ? $user->getUsername() : 'annoymous'];
    }
}