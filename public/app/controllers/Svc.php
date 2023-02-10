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
    //method 1
    $db = dbC::getDB();
    $data = $db->all("SELECT * FROM testtable WHERE id=:id ;", ["id" => "12"]);

    //or  method 2
    $data = QQ()->t("testtable")->s("*")->w("id=:id")->all(["id" => "12"]);
    $user = new UserM("ali", "ali@local");

    phpH::json($data);
  }

  public function set()
  {
    $db = dbC::getDB();

    //method 1
    $q = ["id" => "12", "name" => "roon&ey ", "title" => "hell'o"];
    $fields = dbC::getSetFields($q); //fields="id=:id,name =:name,title=:title"
    $data = $db->set("INSERT INTO testtable SET $fields ON DUPLICATE KEY UPDATE $fields ;", $q);  //insert or update one line

    //or  method 2
    $q = ["id" => "12", "name" => "roon&ey ", "title" => "hell'o"];
    QQ()->t('testtable')->ins($q, dubUpdate: true);

    phpH::json($data);
  }

  public function home()
  {
    $user = new UserM("ali", "ali@local");
    phpH::json($user);
  }
}
