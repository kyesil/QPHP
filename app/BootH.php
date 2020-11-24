<?php
//this is sample bootH load before controllers libraries.

function s($key, $val, $ttl = 10) {
   // return apcu_store($key, $val, $ttl);
}

function g($key) {
   // return apcu_fetch($key);
}
 function checkAllFlood() //require apcu
{ 
   $rid = 'q_' . $_SERVER['REMOTE_ADDR'];
   $r = g($rid);     
   if ($r == null)
      return s($rid, array(time() - 3, 1));
   $t = time() - $r[0];
   $r[1]++;
   if ($t / $r[1] < 0.15 && $r[1] > 100) {
      sleep(3);
      exit('easy');
   }
   if ($r[1] > 5000)
      $r = null; 
   s($rid, $r, 10);
}

function checkFloodBy($max, $fname) {  //require  apcu
    $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
    $r = g($rid);
    $t = time() - $r;
    if ($t < $max) {
        sleep(3);
        exit('easy');
    }
    s($rid, time());
}