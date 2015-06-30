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
function nz($a, $nz) {
    return $a ? $a : $nz;
}