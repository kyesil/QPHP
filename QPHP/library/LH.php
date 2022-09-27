<?php
class LH
{
    public static $dict = [];
    public static $lid = '';
    public static $cacheTimeout = 3600;
    public static $langFile;

    public static function t($key) //transform function for view or actions 
    {
        if (isset(LH::$dict[$key]))
            return LH::$dict[$key];
        else {
            LH::missingKey($key);
            return  $key;
        }
    }


    public static function langLoad($filePath)
    {
        if (!file_exists($filePath)) {
            Q_APP::error(404, 'Error lang file not found:' . $filePath);
            return LH::$dict =  [];
        }

        $content = file_get_contents($filePath);
        LH::$dict =  json_decode($content, true);
    }


    public static function langCheck($lid)
    {
        LH::$lid = $lid;
        $isempty = empty(LH::$dict);
        if (!$isempty) return;  //dont touch next reload.
        LH::$langFile = LANG_FOLDER . escapeshellcmd(LH::$lid) . '.json';
        if (!function_exists('apcu_store')) return LH::langLoad(LH::$langFile); //can cache ?

        $cachetime = LH::g('lang_' . $lid . '_time');
        if (time() - $cachetime <= LH::$cacheTimeout) {
            LH::$dict =  LH::g('lang_' . $lid . '_data');
            if (!empty(LH::$dict)) return;
        }

        if (!file_exists(LH::$langFile))  return LH::$dict = [];
        $changeTime = filemtime(LH::$langFile);

        if (!$cachetime || $changeTime > $cachetime || time() - $cachetime > LH::$cacheTimeout) {
            LH::langLoad(LH::$langFile);
            LH::s('lang_' . $lid . '_data', LH::$dict, 0);
            LH::s('lang_' . $lid . '_time', time(), 0);
        }
    }

    public static function missingKey($key)
    {
        if (!DEV_MODE)
            return;
        if (file_exists(LANG_FOLDER . 'missingKey.php')) {
            include LANG_FOLDER . 'missingKey.php';
            missingKey($key);
        }
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
