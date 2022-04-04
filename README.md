# QPHP MVC Based Api Router
QUICK PHP 7+ MVC based router for create api. nginx or apache2
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
PHP 7+ Apache2 or Nginx

- for ngnix: put your server{} tag inside :
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
- for apache ready (look .htaccess file)

