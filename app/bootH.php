<?php



function s($key, $val, $ttl = 10) {
    return apcu_store($key, $val, $ttl);
}

function g($key) {
    return apcu_fetch($key);
}

function langt($param) {
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