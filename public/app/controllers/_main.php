<?php

function _main($cont) //this run before controller/action run. like bootstrap controller
{
    if(!isset($cont->lang))$cont->lang='en';
    LH::langCheck($cont->lang);
}
