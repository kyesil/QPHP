<?php
class LH
{
    public static $dict = [];
    public static $lid = '';
    public static $cacheTimeout = 3600;

    public static function t($key) //transform function for view or actions 
    {
        if (isset(LH::$dict[$key]))
            return LH::$dict[$key];
        else {
            LH::missKey($key);
            return  $key;
        }
    }


    public static function langLoad($filePath)
    {
        $content = file_get_contents($filePath);
        LH::$dict =  json_decode($content, true);
    }


    public static function langCheck($lid)
    {
        LH::$lid = Q_APP::escapeDir($lid);
        if (!empty(LH::$dict)) return;  //dont touch next reload.
        $langFile = LANG_FOLDER . LH::$lid . '.json';
        $isFileExist = file_exists($langFile);
        if (!$isFileExist) return LH::$dict = [];
        if (!function_exists('apcu_store'))  //can cache ?
            return LH::langLoad($langFile);

        $cachetime = LH::g('lang_' . LH::$lid. '_time');
        if (time() - $cachetime <= LH::$cacheTimeout) {
            LH::$dict =  LH::g('lang_' . LH::$lid . '_data');
            if (LH::$dict && !empty(LH::$dict)) return;
        }

        $changeTime = filemtime($langFile);

        if (!$cachetime || $changeTime > $cachetime || time() - $cachetime > LH::$cacheTimeout) {
            LH::langLoad($langFile);
            LH::s('lang_' . $lid . '_data', LH::$dict);
            LH::s('lang_' . $lid . '_time', time());
        }
    }

    public static function missKey($key)
    {
        if (!defined('DEV_MODE'))
            return;
        // $line = time() . '|'.$key . '|' . $_SERVER['REQUEST_URI'] . "\n";
        // file_put_contents(LANG_FOLDER . LH::$lid . '_.bak', $line,FILE_APPEND);
    }

    public static function langList()
    {

        return [];
    }

    public static function s($key, $val, $ttl = 3600) //memory store write
    {
        return call_user_func_array('apcu_store', [$key, $val, $ttl]);
    }

    public static function g($key) //memory store get
    {
        return call_user_func_array('apcu_fetch', [$key]);
    }
}
