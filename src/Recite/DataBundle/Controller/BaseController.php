<?php

namespace Recite\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Recite\MainBundle\Exception\ReciteException;

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
    public function em($name = null){
        return $this->get('doctrine')->getManager($name);
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderJson($data = null, $message = 'success', $code = 0){
        $json = json_encode(
                ['message' => $message, 'code' => $code, 'data' => $data], 
                JSON_UNESCAPED_UNICODE);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * 
     * @param array $roles
     * @param string $method
     * @throws HttpException
     */
    public function accessFilter($roles = null, $method = null){
        if($method && $this->get('kernel')->getEnvironment() !== 'dev' && 
                $this->get('request')->getMethod() !== strtoupper($method)){

            throw new ReciteException(ReciteException::METHOD_NOT_ALLOW);
        }

        if($roles){
            $security = $this->get('security.context');

            foreach($roles as $role){
                if($security->isGranted($role)) return;
            }

            throw new ReciteException(ReciteException::USER_NO_PERMISSION);
        }
    }
}