<?php
//this router run like apache. it can route alias to another folder.

$urls = parse_url($_SERVER['REQUEST_URI']);
DEFINE('REQPATH',  $urls['path']);


DEFINE('DOCROOT',  is_dir(__DIR__.'/public/') ? __DIR__.'/public/':__DIR__);
DEFINE('INDEX_LIST', ['index.html', 'index.php']);
DEFINE('MVC_INDEX', 'index.php'); //other request which is not found a file will load this file
DEFINE('ALIAS_LIST', [
    '/beta' => '../appsWeb/beta/',
]);


$_SERVER['DOCUMENT_ROOT']=DOCROOT;
$RPATH = DOCROOT . REQPATH;
foreach (ALIAS_LIST as $a => $r) {
    $ac = strlen($a);
    if (str_starts_with(REQPATH, $a)) {
        $RPATH  = $r . substr(REQPATH,$ac);
        $_SERVER['DOCUMENT_ROOT']=$r;
        break;
    }
}

route($RPATH);

function route($path)
{  prints($path);
    if (is_file($path)) {
        header('Content-type: ' . get_mime_type($path));
        require($path);
    } elseif (is_dir($path)) {
    
        if ($path != '' && !str_ends_with($path, '/'))
            $path .= '/';
        index($path);
    } else require(DOCROOT.MVC_INDEX);

    exit();
}

function index($dir)
{      
    foreach (INDEX_LIST as $key => $fn) {
        $fp =$dir . '/' . $fn;  
        if (is_file($fp)) {
            header('Content-type: ' . get_mime_type($fp));
            require($fp);
            return;
        }
    }
    err(404); //if does not match
}
function err($code)
{
    http_response_code($code);
    exit("Err code: $code");
}

function get_mime_type($filename)
{
    //more type :https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
    $idx = explode('.', $filename);
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode - 1]);
    $mimet = array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'csv' => 'text/csv',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        '7z' => 'application/x-7z-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4',
        // docs
        'pdf' => 'application/pdf',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',
    );

    if (isset($mimet[$idx])) {
        return $mimet[$idx];
    } else {
        return 'application/octet-stream';
    }
}

function prints(...$args)
{
  foreach ($args as $arg) {
    if (is_object($arg) || is_array($arg) || is_resource($arg)) {
      $output = print_r($arg, true);
    } else {
      $output = (string) $arg;
    }
    fwrite(fopen('php://stdout', 'w'), $output."\n");
  }
}