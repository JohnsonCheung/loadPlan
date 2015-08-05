<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 31/5/2015
 * Time: 16:52
 */

function launch($cmd) {
    exec("start $cmd");
}

function is_server()
{
    return isset($_SERVER['HTTP_HOST']);
}

function logFt($varNm, $val, $ft)
{
    $fd = fopen("c:/temp/$ft", "a");
    fwrite($fd, "\r\n-------------\r\n");
    fwrite($fd, $varNm . "\r\n");
    $o = print_r($val, true);
    fwrite($fd, $o);
    fclose($fd);
}
