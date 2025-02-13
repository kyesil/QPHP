<?php

class bootH
{
    static function s($key, $val, $ttl = 6000)
    { 
        if (function_exists('apcu_store'))
            return apcu_store($key, $val, $ttl);
        else return null;
    }

    static function g($key)
    {
        if (function_exists('apcu_fetch'))
            return apcu_fetch($key);
        return null;
    }
    static function d($key)
    {
        if (function_exists('apcu_delete'))
            return apcu_delete($key);
        return null;
    }


    static function floodTime($fname, $second)
    {
        $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
        $r = bootH::g($rid);
        $dt = time() - $r;
        if ($dt < $second) {
            phpH::err('easy');
        }
        bootH::s($rid, time());
    }
    static function floodCount($fname, $second, $count)
    {
        $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
        $last = bootH::g($rid);
        if (!isset($last['c'])) return bootH::s($rid, ['c' => 0, 't' => time()]);

        $dt = time() - $last['t'];
        if ($dt <= $second) {
            if ($last['c'] > $count)
                phpH::err('easy');
        } else if ($last['c'] < $count)  bootH::s($rid, ['c' => 0, 't' => time()]);

        bootH::s($rid, ['c' => $last['c'] + 1, 't' => $last['t']]);
    }
    static function floodAvg($fname, $maxavg, $countafter = 30, $resetsecond = 30)
    { // istek sayısı $countafter ulaştığında saniyedeki request sayısı $max avgden fazlaysa durdur.  soğuması için ya avg düşecek 
        $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
        $last = bootH::g($rid);
        if (!isset($last['c'])) return bootH::s($rid, ['c' => 0, 't' => time()]);

        $dt = time() - $last['t'];
        $avg = $last['c'] / $dt; // req/sec

        if ($avg > $maxavg && $last['c'] > $countafter)
            phpH::err('easy');
        else if ($dt > $resetsecond && $avg < $maxavg / 2)
            bootH::s($rid, ['c' => 0, 't' => time()]); //reset
        else
            bootH::s($rid, ['c' => $last['c'] + 1, 't' => $last['t']]);
    }

    static function floodget($fname)
    {
        $rid = 'q_' . $_SERVER['REMOTE_ADDR'] . $fname;
        return bootH::g($rid);
    }
    static function cacheInfo()
    {
        qwput(apcu_cache_info());
    }
}
