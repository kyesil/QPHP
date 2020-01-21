<?php



function s($key, $val, $ttl = 10) {
   // return apcu_store($key, $val, $ttl);
}

function g($key) {
   // return apcu_fetch($key);
}
 function _initSession()
{
   $rid = 'q_' . $_SERVER['REMOTE_ADDR'];
   $r = g($rid);      //var_dump($r, $t / $r[1], $r[1]);
   if ($r == null)
      return s($rid, array(time() - 3, 1));
   $t = time() - $r[0];
   $r[1]++;
   if ($t / $r[1] < 0.15 && $r[1] > 100) {
      sleep(3);
      exit('easy');
   }
   if ($r[1] > 5000)
      $r = null; //geçerli sorgudan sonra sıfırlama
   s($rid, $r, 10);
}

function langt($param) {
    if(!isset($_SESSION)) return;
    $kk = "lang_$_SESSION[_langid]_data";
    if (isset($GLOBALS[$kk])){
        $ldata = $GLOBALS[$kk];  
    }
    else {
        $ldata = g($kk);
        $GLOBALS[$kk] = $ldata;
    }

    return (isset($ldata[$param])?$ldata[$param]:$param);
}

function checkFlood($max, $fname) {
    $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
    $r = g($rid);
    $t = time() - $r;
    if ($t < $max) {
        sleep(3);
        exit('easy');
    }
    s($rid, time());
}