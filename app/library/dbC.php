<?php

class dbC
{

  public $pdo;
  public $stm;

  public function __construct($host = DB_HOST, $dbname = DB_DB, $user = DB_USER, $pass = DB_PASS)
  {
    try {
      $this->pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET @@sql_mode='';"
        )
      );
    } catch (PDOException $e) {
    }
    return $this->pdo;
  }
  public static function getDB()
  {
    $db = new dbC();

    if (!$db->pdo)  phpH::err('err_db_conn', 501);
    return $db;
  }

  /** escape string */
  public static function escapeFields($value)
  {
    return preg_replace('/[^A-Za-z0-9_]/', '', $value);
  }


  /** return escaped params for pdo like this : "field1=:field1,field2=:field2"  */
  public static function getSetFields($q)
  {
    $fields = '';
    if (is_array($q))
      foreach ($q as $k => $v) {
        if ($k[0] == '_') continue;
        $k = dbC::escapeFields($k);
        $fields .= "$k =:$k,";
      }
    $fields = rtrim($fields, ',');
    return $fields;
  }

  /** return escaped params for pdo like this : "(field1,field2,field2) VALUES (:field1,:field2,:field2)"  */
  public static function getInsertFields($q)
  {
    $fields = '';
    $values = '';
    if (is_array($q))
      foreach ($q as $k => $v) {
        if ($k[0] == '_') continue;
        $k = dbC::escapeFields($k);
        $fields .= "$k,";
        $values .= ":$k,";
      }
    $fields = rtrim($fields, ',');
    $values = rtrim($values, ',');
    return '(' . $fields . ') VALUES (' . $values . ')';
  }

  // public static function escapeValues($value)
  // {
  //   return addslashes($value);
  // }


  /** return escaped params for pdo like this : "field1='value1',field2='value1'" */
  // public static function getValueFields($q)
  // {
  //   $fields = '';
  //   if (is_array($q))
  //     foreach ($q as $k => $v) {
  //       if ($k[0] == '_') continue;
  //       $k = dbC::escapeFields($k);
  //       $v = dbC::escapeValues($v);
  //       if ($v == "NaN") $v = "NULL";
  //       else $v = "'$v'";
  //       $fields .= "$k =$v,";
  //     }
  //   $fields = rtrim($fields, ',');
  //   return $fields;
  // }


  // public static function escapeArray($q)
  // {
  //   $result = [];
  //   if (is_array($q))
  //     foreach ($q as $k => $v) {
  //       if ($k[0] != '_') {
  //         $k = dbC::escapeFields($k);
  //         $v = dbC::escapeValues($v);
  //       }
  //       $result[$k] = $v;
  //     }
  //   return $result;
  // }


  public function exec($sql, $params)
  {
    $this->stm = $this->pdo->prepare($sql);
    if (is_array($params))
      foreach ($params as $k => $v) {
        if (strpos($sql, $k) === false) {
          unset($params[$k]);
          continue;
        }
        $this->stm->bindParam(':' . $k, $v);
      }
    $r = $this->stm->execute($params);
    return $r;
  }
  public function set($sql, $params = null)
  {
    try {
      $r = [];
      $this->exec($sql, $params);
      $r['ar'] = $this->stm->rowCount();
      $r['li'] = $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      $r = $this->getError($e);
    }
    return $r;
  }
  public function gets($sql, $params = null)
  {
    try {
      $this->exec($sql, $params);
      $r = $this->stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // var_dump($e);
      $r = $this->getError($e);
    }
    return $r;
  }
  public function get($sql, $params = null)
  {
    try {
      $this->exec($sql, $params);
      $r = $this->stm->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $r = $this->getError($e);
    }
    return $r;
  }
  public function getError($e)
  {
    return ['err' => $e->getCode(), 'msg' => $e->getMessage()];
  }
}
