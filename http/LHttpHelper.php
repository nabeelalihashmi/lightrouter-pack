<?php

namespace IconicCodes\LightHttp;

class LHttpHelper {
    public static function arrayToObject($array = []) {
        return json_decode(json_encode($array));
    }
}