<?php

namespace Aliegon\Request;

class Request
{
    
    private $get;

    private $post;

    public function __construct()
    {
        $this->loadGet();
        $this->loadPost();
    }

    private function loadGet()
    {
        $this->get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $_GET = null;
    }

    private function loadPost()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            switch ($_SERVER['CONTENT_TYPE']) {
                case 'application/json':
                case 'application/x-json':
                    $this->post = $this->getJsonPost();
                    break;
                default:
                    $this->post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    break;
            }
        }

        $_POST = null;         
    }

    private function getJsonPost()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getPost($key = false)
    {
        if($key === false)
            return $this->post;

        return $this->post[$key];
    }

    public function getGet($key = false)
    {
        if($key === false)
            return $this->get;

        return $this->get[$key];
    }
}