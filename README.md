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

# Install
PHP 7+ required. 

# Usage with PHP Built-in Server 
- php -S localhost:80 -t ./
or work with our router for custom redirects look router.php
- php -S localhost:80 router.php

# Usage with Nginx 
- for ngnix: put your server{} tag inside :
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
# Usage with Apache 
- activate RewriteEngine
- apache ready (look .htaccess file)

# Usage with Docker 
- Look dev.Dockerfile and  docker-compose.yml
- docker compose up
- docker compose up --build --force-recreate

