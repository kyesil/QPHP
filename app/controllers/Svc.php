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
   

    $data = $db->gets("SELECT * FROM testtable WHERE id=:id ;", ["id" => "12"]);
    $user= new UserM("ali","ali@local");

    phpH::json($data);
  }

  public function set()
  {
    $q=["id"=>"12","name" => "roon&ey ","title"=>"hell'o"]; // $q=$_POST;
    $db = dbC::getDB();

    //method 2 secure way
    $fields=dbC::getSetFields($q); //fields="id=:id,name =:name,title=:title"
    $data = $db->set("INSERT INTO testtable SET $fields ON DUPLICATE KEY UPDATE $fields ;",$q);  //insert or update one line

    // //method 1 
    // $fields=dbC::getValueFields($q);  //fields="id='12',name ='rooney ',title='hello'"
   
    // $data = $db->set("INSERT INTO testtable SET $fields ON DUPLICATE KEY UPDATE $fields;");

    //method 3
    // $fields=dbC::getInsertFields($q); //fields="(id,name,title) VALUES (:id,:name,:title)"
    // $data = $db->set("INSERT INTO testtable $fields;",$q);

    phpH::json($data);
  }
}
