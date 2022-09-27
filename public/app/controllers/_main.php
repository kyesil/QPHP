<?php

function _main($cont) //this run before controller/action run. like bootstrap controller
{   
    //multi language activate before using  uncomment below
    // if(!isset($cont->lang))$cont->lang='en';
    // LH::langCheck($cont->lang);
}
