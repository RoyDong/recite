<?php

namespace Recite\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class BaseController extends Controller
{
    /**
     * 
     * @param string $name
     * @return Doctrine\ORM\EntityRepository;
     */
    public function __get($name){
        return $this->get('doctrine')->getRepository('ReciteDataBundle:'.$name);
    }

    /**
     * get doctrine manager
     * 
     * @param string $name
     * @return EntityManager;
     */
    protected function em($name = null){
        return $this->get('doctrine')->getManager($name);
    }

    protected function renderJson($data = null){
        if (is_array($data) && 0 === count($data)) {
            $data = new \ArrayObject();
        }

        // Encode  ', &, and " for RFC4627-compliant JSON, which may also be embedded into HTML.
        if($data){
            $json = json_encode($data, JSON_UNESCAPED_UNICODE );
        }else{
            $json = '';
        }

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}