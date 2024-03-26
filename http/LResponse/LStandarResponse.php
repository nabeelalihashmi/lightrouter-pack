<?php 

namespace IconicCodes\LightHttp\LResponse;
use IconicCodes\LightHttp\Interfaces\IResponse;


class LStandarResponse implements IResponse {
    private $__data;
    private $__headers = [];
    private $__status = 200;

    public function __construct($data = null, $status = 200, $headers = []) {
        $this->__data = $data;
        $this->__status = $status;
        $this->__headers = $headers;
    }

    public function send() {
        http_response_code($this->__status);
        foreach ($this->__headers as $key => $value) {
            header($key . ': ' . $value);
        }
        echo $this->__data;
    }
}

