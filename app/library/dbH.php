<?php
class dbH {

    public static function connect($dn = DB_DB) {
        if (!$db = new mysqli(DB_HOST, DB_USER, DB_PASS, $dn,DB_PORT))
            if (mysqli_connect_errno())
                exit('db_connect_error');
        $db->set_charset('utf8');
        return $db;
    }

    static function readAll($reader) {/* bu bulunan tüm tabloyu tablo dizisi olarak döndürür. */

        return $reader->fetch_all(MYSQLI_ASSOC);
    }

    static function readAllGroup($reader, $keyname) {/* bir keye göre verileri o key değerine göre gruplar. */
        $result = array();
        while ($row = $reader->fetch_assoc())
            $result[$row[$keyname]] = $row;
        return $result;
    }

    static function readAllDic($reader, $keyname, $keyvalue) { /* read type 200 */
        $result = array();

        while ($row = $reader->fetch_assoc())
            $result[$row[$keyname]] = $row[$keyvalue];

        return $result;
    }





    public static function execMultiQuery($db, $q, $typer = false, $k = null, $v = null, $a = null) {//çoklu sorgu yapılabilir method sonuçtipini array olarak alır. geriye sorgu srasına göre sonuç dizisi verir.
        $i = 0;

        if ($db->multi_query($q)) {
            $result = array();
            do {
                try {
                    if ($reader = $db->use_result()) {

                        switch ($typer[$i]) {//sonuç tipleri
                            case 1:$r = $reader->fetch_assoc();
                                break;
                            case 2:$r = dbH::readAll($reader);
                                break;
                            case 3:$r = dbH::readAllDic($reader, $k, $v);
                                break;
                            case 4:$r = dbH::readAllGroup($reader, $k);
                                break;
                            case 5:$r = dbH::readGroup2Dic($reader, $k, $v, $a);
                                break;
                            case 6:$r = $reader->fetch_row()[0];
                                break;
                            default: $r = $db->affected_rows;
                                break;
                        }

                        $result[$i] = $r;
                        $reader->free();
                    }
                } catch (Exception $exc) {
                    
                }
                $i++;
            } while ($db->more_results() && $db->next_result());
        }

        return $result;
    }

}
