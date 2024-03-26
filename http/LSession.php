<?php

namespace IconicCodes\LightHttp;
use IconicCodes\LightHttp\LHttpHelper;
use IconicCodes\LightHttp\LSecureSessionHandler;

class LSession {

  public static function register_secure_handler() {
    session_set_save_handler(new LSecureSessionHandler());
  }

  public static function flash($key, $val = null) {
    self::pre();
    if ($val === null) {
      $val = $_SESSION[$key] ?? null;
      if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
      }
      return $val;
    } else {
      $_SESSION[$key] = $val;
    }
  }

  public static function pre() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  public static function exists($name) {
    self::pre();
    return (isset($_SESSION[$name])) ? true : false;
  }

  public static function get($name) {
    self::pre();
    if (!self::exists($name)) return NULL;
    return is_array($_SESSION[$name]) ?  LHttpHelper::arrayToObject($_SESSION[$name]) :  $_SESSION[$name];
  }

  public static function set($name, $value) {
    self::pre();
    return $_SESSION[$name] = $value;
  }

  public static function delete($name) {
    self::pre();
    if (self::exists($name)) {
      unset($_SESSION[$name]);
    }
  }

  public static function uagent($no_version = true) {
    self::pre();
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    if ($no_version) {
      $regx = '/\/[a-zA-Z0-9.]+/';
      $uagent = preg_replace($regx, '', $uagent);
    }
    return $uagent;
  }
}
