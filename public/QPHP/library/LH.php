<?php
class LH
{
    public static $dict = [];
    public static $lid = '';
    public static $cacheTimeout = 3600;

    public static function t($key) //transform text function for view or actions 
    {
        if (isset(LH::$dict[$key]))
            return LH::$dict[$key];
        else {
            //LH::missKey($key); //activate the keys which is not fount  put a file
            return  $key;
        }
    }


    public static function langLoad($langFile)
    {
        $isFileExist = file_exists($langFile);
        if (!$isFileExist) return LH::$dict = [];

        if (LANG_MODE == "php") {
            include_once $langFile; //export $LDATA
            return $LDATA;
        }
        $content = file_get_contents($langFile);
        return json_decode($content, true);
    }

    public static function langCheck($lid)
    {
        LH::$lid = Q_APP::escapeDir($lid);
        if (!empty(LH::$dict)) return;  //dont touch next reload.
        $langFile = LANG_FOLDER . LH::$lid . '.' . LANG_MODE; // /langs/en.json or   /langs/en.php
        return LH::$dict = LH::langLoad($langFile);
    }

    public static function missKey($key)
    {
        if (!defined('DEV_MODE'))
            return;
        $mfile = LANG_FOLDER . LH::$lid . '_missed.txt';
        if (file_exists($mfile)) {
            $content = file_get_contents($mfile);
            $missed = json_decode($content, true);
            $missed[$key] = $_SERVER['REQUEST_URI'];
        } else {
            $missed = [$key => $_SERVER['REQUEST_URI']];
        }
        file_put_contents(LANG_FOLDER . LH::$lid . '_missed.txt', json_encode($missed));
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
