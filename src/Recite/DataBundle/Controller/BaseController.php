<?php

namespace Recite\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Doctrine\ORM\EntityManager
     */
    protected function em($name = null){
        return $this->get('doctrine')->getManager($name);
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderJson($data = null){
        if (is_array($data) && 0 === count($data)) {
            $data = new \ArrayObject();
        }

        $json = $data ? json_encode($data, JSON_UNESCAPED_UNICODE) : '';
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}