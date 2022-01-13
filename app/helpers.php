<?php
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function select_set($type, $value)
{
    $html = '';
    if($type==$value){
        $html = ' selected="selected" ';
    }
    return $html;
}
