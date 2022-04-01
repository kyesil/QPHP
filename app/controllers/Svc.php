<?php
class SvcC extends Q_Controller
{
  function _init()
  {
     $this->apiEnable();
  }
  public function __destruct()
  {
  }
  public function get()
  {
    $db = dbC::getDB();
   

    $data = $db->gets("SELECT * FROM users WHERE id=:id ;", ["id" => "194"]);
    $user= new UserM("ali","ali@local");
    
    $q=["name" => "ali ","title"=>"hello"];
    
    $fields=dbC::getValueFields($q);
    $data = $db->set("INSERT INTO testtable SET $fields;");

    // $fields=dbC::getSetFields($q);
    // $data = $db->set("INSERT INTO testtable SET $fields;",$q);

    // $fields=dbC::getInsertFields($q);
    // $data = $db->set("INSERT INTO testtable $fields;",$q);

    phpH::json($data);
  }
}
