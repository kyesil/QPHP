<?php
class LH
{
    public static $dict = [];
    public static $lid = 'tr';
    public static $cacheTimeout = 3600;


    public static function t($key)//transform function for view or actions 
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
        if (!file_exists($filePath))
            return LH::$dict =  [];
        $content = file_get_contents($filePath);
        LH::$dict =  json_decode($content, true);
    }


    public static function langCheck($lid)
    {
        LH::$lid=$lid;
        $isempty = empty(LH::$dict);
        if (!$isempty) return;  //dont touch next reload.
        $filePath = LANG_FOLDER . escapeshellcmd(LH::$lid) . '.json';
        if (!function_exists('apcu_store')) return LH::langLoad($filePath); //can cache ?

        $cachetime = LH::g('lang_' . $lid . '_time');
        if (time() - $cachetime <= LH::$cacheTimeout) {
            LH::$dict =  LH::g('lang_' . $lid . '_data');
            if (!empty(LH::$dict)) return;
        }

        if (!file_exists($filePath))  return LH::$dict = [];
        $changeTime = filemtime($filePath);

        if (!$cachetime || $changeTime > $cachetime || time() - $cachetime > LH::$cacheTimeout) {
            LH::langLoad($filePath);
            LH::s('lang_' . $lid . '_data', LH::$dict, 0);
            LH::s('lang_' . $lid . '_time', time(), 0);
        }
    }

    public static function missingKey($key)
    { // if dont match any lang key this method log lang keys somewhere  only dev mode  disable for now
        // if (!DEV_MODE)
        //     return;
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
