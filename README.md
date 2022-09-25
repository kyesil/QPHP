# QPHP MVC Based Api Router
QUICK PHP 7+ MVC based router for create api. Works with :
 - Docker
 - PHP Built-in Server 
 - Nginx
 - Apache2
# Specs
- Create a service api controller. looks like standard MVC 
- Simplified PDO helper. example in app\controllers\Svc.php
- Support base URL/PATH like www.example.com/en/home/home or  www.example.com/mydir/home/home. look here: app/config.php
- Support default controller or/and action example www.example.com/ means home controller and home action.
- Support preselect controller. route only one controller's actions 
- Support send variables to views form actions.
- Render custom view
- Execute custom action
- Multi language support from json file. look config.php file. For set and start language: look controllers/_main.php  file 

# Install
PHP 7+ required. 
- Your site files should be /public folder.
- /public folder an example for quick start.
- Apache, nginx or built-in server  should be pointed /public folder. you can change /public folder name depends your hosting.
- Every php  request start from /public/index.php file.
- QPHP folder contain MVC engine and libaries. it can be moved public folder.  look /public/index.php
- /langs folder contain multi language files. it runs from /public/app/controllers/_main.php
- /public/app/controllers/_main.php means: run before every request to controllers and action
- supporst custom routes look  /public/app/qroutes.php

# Usage with PHP Built-in Server 
- php -S localhost:8081 -t ./public
- or work with our router for custom redirects look router.php :
- php -S localhost:8081 router.php

# Usage with Nginx 
- for ngnix: put your server{} tag inside :
```
root ....../public
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
# Usage with Apache 
- config file point: DocumentRoot "...../public"
- activate RewriteEngine
- apache ready (look public/.htaccess file)

# Usage with Docker 
- Look dev.Dockerfile and  docker-compose.yml
- docker compose up
- docker compose up --build --force-recreate

