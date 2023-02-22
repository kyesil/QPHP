<?php
function QQ($dbname = DB_DB): dbQuery
{
  $dbq = new dbQuery($dbname);
  return $dbq;
}

class dbQuery
{
  private string $table;
  private string $joinSql = "";
  private string $select = "*";
  private string $where = "";
  private string $order = "";
  private string $group = "";
  private string $limit = "LIMIT 1000"; //default
  public ?dbC $dbc = null;
  public function __construct($dbname)
  {
    $this->dbc = dbC::getDB($dbname);

    if ($this->dbc) return $this;
    else   return null;
  }
  public function s($select): dbQuery
  {
    $this->select = $select;
    return $this;
  }
  public function t($table): dbQuery
  {
    $this->table = $table;
    return $this;
  }

  public function w($compare): dbQuery
  {
    $this->where =  "WHERE $compare";
    return $this;
  }
  public function o($field): dbQuery
  {
    $this->order =  "ORDER BY $field";
    return $this;
  }

  public function g($field): dbQuery
  {
    $this->group =  "GROUP BY $field";
    return $this;
  }
  public function l($limit): dbQuery
  {
    $this->limit = "LIMIT " . $limit;
    return $this;
  }
  public function j($table, $compare, $type = 'i'): dbQuery
  {
    $types = [
      "i" => "INNER",
      "l" => "LEFT",
      "o" => "OUTER",
      "r" => "INNER",
    ];
    $this->joinSql .= $types[$type] . " JOIN $table ON $compare \n";
    return $this;
  }

  public function del($params, $getSql = false)
  {
    $dt = "";
    if (!empty($this->joinSql))
      $dt = "$this->table";
    $sql = "DELETE $dt FROM $this->table $this->joinSql  $this->where $this->limit;";

    return  $getSql ? $sql : $this->dbc->set($sql, $params);
  }
  public function ins($params, $dubUpdate = null)
  {
    $setFields = dbC::getSetFields($params);
    if ($dubUpdate === true) $dubUpdate = " ON DUPLICATE KEY UPDATE  $setFields ";
    elseif (strlen($dubUpdate) > 3) $dubUpdate = "ON DUPLICATE KEY UPDATE $dubUpdate ";
    else  $dubUpdate = "";
    $sql = "INSERT INTO  $this->table  SET $setFields $dubUpdate;";
    //return  $sql;
    return  $this->dbc->set($sql, $params);
  }
  public function upd($params, $getSql = false)
  {
    $setFields = dbC::getSetFields($params);
    $sql = "UPDATE $this->table SET $setFields  $this->joinSql  $this->where $this->limit;";
    return  $getSql ? $sql : $this->dbc->set($sql, $params);
  }

  public function all($params = null, $getSql = false)
  {
    $sql = "SELECT $this->select FROM $this->table $this->joinSql  $this->where  $this->group $this->order  $this->limit;";
    return  $getSql ? $sql : $this->dbc->all($sql, $params);
  }
  public function one($params, $getSql = false)
  {
    if (!isset($this->limit)) $this->limit = "LIMIT 1";
    $sql = "SELECT $this->select FROM $this->table $this->joinSql  $this->where $this->group $this->order  $this->limit;";
    return  $getSql ? $sql : $this->dbc->one($sql, $params);
  }
  public function cell($params, $index = 0, $getSql = false)
  {
    $val = $this->one($params, $getSql);
    if (is_array($val))
      return $val[$index] ?? array_values($val)[$index] ?? false;
  }
}
