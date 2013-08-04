<?php

class acpt {

  function __construct() {}

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    } else {
      return null;
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
      $this->$property = $value;
    }

    return $this;
  }

  function make_computer_name($name) {
    $pattern = '/(\s+)/';
    $replacement = '_';
    $computerName = preg_replace($pattern,$replacement,strtolower(trim($name)));
    return $computerName;
  }

  function test_for($data, $error, $type = 'string') {
    switch($type) {
      case 'array' :
        if(isset($data) && !is_array($data) ) die('ACPT Error: '. $error);
        break;
      case 'bool' :
        if(isset($data) && !is_bool($data) ) die('ACPT Error: '. $error);
        break;
      default:
        if(empty($data) && !is_string($data)) die('ACPT Error: '. $error);
        break;
    }
  }

}