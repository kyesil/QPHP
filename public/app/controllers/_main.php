<?php

function _main($cont) //this run before controller/action run. like bootstrap controller
{   
    //multi language activate before using  uncomment below
    if(empty($cont->lang)){
        $cont->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    LH::langCheck($cont->lang);
}
