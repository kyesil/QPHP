<?php

class sessH
{
    static  $users = [
        "admin:admin34"=> 100,
        "opr:opr34"=> 10,
    ];
    static function check($level = 10, $timeout = 3000)
    {
        if (isset($_COOKIE['sessid']) || isset($_GET['sessid'])) {
            $cookieVal = $_GET['sessid'] ?? $_COOKIE['sessid'];
            $val = bootH::g('sessid_' . $cookieVal);

            if (empty($val['level']))
                return self::err('notlogin');
            if (time() - $val['time'] > $timeout) return self::err('timeout');

            if ($val['level'] >= $level) return $cookieVal;
            else self::err('notlevel');
        }

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {  //şifre girilmişse
            $ulevel = self::checkUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            if (!$ulevel) return self::err('nouser');
            if ($ulevel < $level) return self::err('notauth');
            return self::start(level: $ulevel, ttl: $timeout); //oturumu başlat
        } else { //şifre girilmemişse yeni bir realm ile şifre sorar şifre girildiğinde request baştan başlar.  $_SERVER['PHP_AUTH_xxx'] e gelir.
            $realm = phpH::randomStr(10, onlynumber: false) . "_" . time();
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            self::err('notlogin');
        }
    }
    static function start($level, $ttl)
    {
        $str = phpH::randomStr(20, onlynumber: false) . "_" . time();
        $arr = ['time' => time(), 'level' => $level];
        bootH::s('sessid_' . $str, $arr, 0);
        setcookie('sessid', value: $str, expires_or_options: time() + 60 * 60 * 24 * 30, path: "/");
        return $str;
    }

    static function checkUser($user, $pass)
    {
        return self::$users["$user:$pass"] ?? null;
    }
    static function err($msg)
    {
        self::logout();
        setcookie('sessid', value: "", expires_or_options: time() - 20, path: "/");//sadece hata verdiğinde oturumu yenilemek için cookie silsin
        phpH::err($msg . ": Refresh to relogin");
    }

    static function logout()
    {
        $cookieVal = $_GET['sessid'] ?? $_COOKIE['sessid'] ?? null;
        if (isset($cookieVal))
            bootH::d('sessid_' . $cookieVal);
        header('HTTP/1.0 401 Unauthorized');//çıkış yapabilmek için cookie silmiyoruz. apcu daki değeri bulamadında çıkış yaptığını anlayacak.
    }
}
