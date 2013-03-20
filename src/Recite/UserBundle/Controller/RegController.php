<?php

namespace Recite\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/reg")
 * @Template()
 */
class RegController extends Controller
{
    /**
     * @Route("/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        if($this->get('request')->get('_f') == 'json'){
            return $this->render('ReciteUserBundle:Reg:hello.json.twig', ['name' => $name]);
        }

        return array('name' => $name);
    }
}
