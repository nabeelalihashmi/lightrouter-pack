<?php

namespace IconicCodes\LightHttp;

use finfo;
use IconicCodes\LightHttp\LFile;

class LRequest {
    public $server;
    public $method;
    public $request;
    public $primary_request;
    public $files = [];
    public $input;
    public $ip;


    public function __construct() {
        $this->server = (object) $_SERVER;
        $this->method =  $_POST["_method"] ?? $_SERVER['REQUEST_METHOD'];
        $this->input =  (object) ["raw" => file_get_contents("php://input"), "object" => json_decode(file_get_contents("php://input"))];
        $this->ip = self::getIp();
        $this->request =  [
            "request" => [],
            "post" => [],
            "get"  => [],
            "put" => [],
            "delete" => [],
            "patch"  => [],
            "head" => []
        ];

        foreach ($this->request as $k => $v) {
            $k = (in_array($k, ["put", "delete", "patch"])) ? "post" : $k;
            $k = (in_array($k, ["head"])) ? "get" : $k;
            switch ($k) {
                case 'get':
                    $this->request[$k] = $this->arrayToObject($_GET);
                    break;

                case 'post':
                    $this->request[$k] = $this->arrayToObject($_POST);
                    break;

                case 'request':
                    $this->request[$k] = $this->arrayToObject($_REQUEST);
                    break;
            }
        }

        $this->request = (object) $this->request;


        $finfo = @new finfo(FILEINFO_MIME);
        foreach ($_FILES as $file) {
            $file_ = new LFile($file);
            $file_->expected_mimes = [];
            $this->files[$file] = $file_;
        }

        $this->files = (object) $this->files;

        $_method = strtolower($this->method);
        if (in_array($_method, ["put", "patch", "delete"])) {
            $this->request->$_method = $this->request->post;
            $_method = "post";
        } elseif (in_array($_method, ["head"])) {
            $this->request->$_method = $this->request->post;
            $_method = "get";
        }


        $this->primary_request = $this->request->$_method;
    }

    private function arrayToObject($array = []) {
        return json_decode(json_encode($array));
    }

    public static function getIp() {
        $ip_address = 'UNKNOWN';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        return $ip_address;
    }
}
