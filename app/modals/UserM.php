<?php

class UserM
{

  public $name;
  public $email;


  public function __construct($name,$email)
  {
    $this->name=$name;
    $this->name=$email;
  }
  public static function getDB()
  {
    if (!$db = new dbC()) phpH::err(-1);
    return $db;
  }

}
