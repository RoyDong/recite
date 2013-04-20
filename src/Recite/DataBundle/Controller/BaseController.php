<?php

namespace Recite\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    /**
     * 
     * @param array $roles
     * @param string $method
     * @return null
     * @throws AccessDeniedHttpException
     */
    protected function accessFilter($roles = null, $method = null){
        if($method && $this->get('kernel')->getEnvironment() !== 'dev' && 
                $this->getRequest()->getMethod() !== strtoupper($method)){

            throw new HttpException(403, 'method is not allowed');
        }

        if($roles){
            $security = $this->get('security.context');

            foreach($roles as $role){
                if($security->isGranted($role)) return;
            }

            throw new HttpException(403, 'no permissions');
        }
    }
}