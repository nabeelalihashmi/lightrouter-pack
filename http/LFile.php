<?php
namespace IconicCodes\LightHttp;

use Exception;

class LFile {

    // $_FILES['key']

    private $file;
    public $expected_mimes = [];
    private $mime;
    public $mime_short;
    private $check_mime = false;
    public $encoding;

    public function __construct($file, $check_mime = false) {
        $this->file = $file;
        $this->mime = mime_content_type($file['tmp_name']);
        $this->mime_short = substr($this->mime, 0, strpos($this->mime, '/'));
        $this->check_mime = $check_mime;
        $this->encoding = mb_detect_encoding(file_get_contents($file['tmp_name']));

    }
    
    public function store ($where = "assets", $newname="", $check_mime = FALSE) {
        if ($newname == "") {
            $newname = time() . uniqid() . "." . pathinfo($this->file['name'] ,  PATHINFO_EXTENSION);
        }

        if ($this->check_mime && count($this->expected_mimes) > 0) {
            if (!in_array($this->mime, $this->expected_mimes)) {
                throw new Exception('MIME DOES NOT MATCH EXPECTED');
            }
        }
        if (move_uploaded_file($this->file['tmp_name'], "$where/$newname")) {
            return $newname;
        } else  {
            return false;
        }
    }
}