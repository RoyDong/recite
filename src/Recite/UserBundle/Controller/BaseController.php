<?php

namespace Recite\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/security")
 */
class BaseController extends Controller
{

    protected function __get($name){
        return $this->get('doctrine')->getRepository('ReciteDataBundle:'.$name);
    }

    /**
     * get doctrine manager
     * 
     * @param string $name
     * @return Manager
     */
    protected function dm($name){
        return $this->get('doctrine')->getManager($name);
    }

    protected function renderJson($data){
        if (is_array($data) && 0 === count($data)) {
            $data = new \ArrayObject();
        }

        // Encode <, >, ', &, and " for RFC4627-compliant JSON, which may also be embedded into HTML.
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE );

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}