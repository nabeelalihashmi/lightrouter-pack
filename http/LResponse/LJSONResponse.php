<?php 

namespace IconicCodes\LightHttp\LResponse;
use IconicCodes\LightHttp\Interfaces\IResponse;

class LJSONResponse implements IResponse {
    private $__data;
    private $__status = 200;
    private $__headers = [];

    public function __construct($data = null, $status = 200, $headers = []) {
        $this->__data = $data;
        $this->__status = $status;
        $this->__headers = $headers;
    }

 
    public function send() {
        http_response_code($this->__status);
        header('Content-Type: application/json');
        foreach ($this->__headers as $key => $value) {
            header($key . ': ' . $value);
        }
        echo json_encode($this->__data);
    }
}


