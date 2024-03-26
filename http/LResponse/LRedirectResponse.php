<?php 

namespace IconicCodes\LightHttp\LResponse;
use IconicCodes\LightHttp\Interfaces\IResponse;

class LRedirectResponse implements IResponse {
    private $__url;
    private $__code;
    function __construct($url, $code = 301) {
        $this->__url = $url;
        $this->__code = $code;
    }

    public function send() {
        if (!headers_sent()) {
            header("Location: $this->__url", true, $this->__code);
        } else {
            echo '<script>window.location.href="' . $this->__url . '";</script>';
        }
    }
}
