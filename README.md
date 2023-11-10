# QPHP Simple MVC Engine

 ## Specs
- Create a service api controller. looks like standard MVC 
- Simplified PDO helper. example in app\controllers\Svc.php
- Support base URL/PATH like www.example.com/en/home/home or  www.example.com/mydir/home/home. look here: `app/qconfig.php`
- Support default controller or/and action example www.example.com/ means home controller and home action.
- Support preselect controller. route only one controller's actions 
- Support send variables to views form actions.
- Render custom view
- Execute custom action
- Multi language support from json file. look config.php file. For set and start language: look `controllers/_main.php`  file 
- Live example here http://qphp.wuaze.com/ on infinityfree host

# Install
Works with :
- Docker
- PHP Built-in Server 
- Nginx
- Apache2

PHP 7+ required. 
- Your site files should be `/public` folder.
- `/public` folder an example for quick start.
- Apache, nginx or built-in server should be pointed `/public` folder. you can change `/public` folder name depends your hosting.
- Every php request start from `/public/index.php` file.
- QPHP folder contain MVC engine and libaries. it can be moved public folder.  look `/public/index.php`
- `/langs` folder contain multi language files. it runs from `/public/app/controllers/_main.php`
- `/public/app/controllers/_main.php` means: run before every request to controllers and action
- supporst custom routes look  `/public/app/qroutes.php`

## Usage with PHP Built-in Server 
- `php -S localhost:8081 -t ./public`
- or work with our router for custom redirects look router.php :
- `php -S localhost:8081 router.php`

## Usage with Nginx 
- for ngnix: put your `server{}` tag inside :
```
root ....../public
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
## Usage with Apache 
- config file point: `DocumentRoot "...../public"`
- activate RewriteEngine
- apache ready (look `public/.htaccess` file)

## Usage with Docker 
- Look `dev.Dockerfile` and  `docker-compose.yml`
- `docker compose up`

# Request Lifecycle
- Apache, nginx or built-in server (webserver app) handle request and redirect site root folder: for example `...../public/`
- if request path dont match a existing file, webserver app redirect all other request to `/public/index.php` file.
- index.php redirect request to  QPHP MVC engine (`/QPHP/run.php`)
- QPHP proccess request according to your qconfig.php file and separate controller, action and params
- QPHP firstly redirect all request to your  main controller method (`/public/app/controllers/_main.php`) then run your controllers and action. 
- If autorender on, QPHP render your view which is match your request from `/public/app/view/`

# Multi Language Feature
- Multi language suport can start with `LH::langCheck('en');`.  
- Then your language var loaded from `/langs/en.json` 
- Then you can use like  `<h3><?=LH::t('sweet_home')?></h3>` from your view or controller; 
- If you need change language call this command  like `LH::langCheck('tr');`
- It can be auto detect your PHP APCU extension and cache your language vars. If you dont have APCU, your lang file  load with every page loaded. 
- For `LH::t('sweet_home')` command, if dont match any key which is your lang file, it return back the key. You se `"sweet_home"`.
- If you need work with url for multi language like `/en/home/page2` , You need activate `LANG_FROM_URL` in your `qconfig.php` file. Then QPHP ignore first 2 char from your request url. then send the lang code as parameter to your controller. Then you should activate in your main controller like `LH::langCheck($cont->lang);` look omur example here: `/public/app/controllers/_main.php`
- For detect the missing key you can activate missingKey module. It can detect missing key and log file look: /langs/

# DB library 
- examples here `/public/app/controllers/svc.php`
# Benchmark only apache nginx
-  npm i -g autocannon
- autocannon -c 100 -d 10 -p 10  http://localhost/svc/home