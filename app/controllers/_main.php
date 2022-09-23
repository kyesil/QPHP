<?php

function _main($cont) //this run before controller/action run. like bootstrap controller
{
    if(!isset($cont->pathlang))$cont->pathlang='en';
    LH::langCheck($cont->pathlang);
}
