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
file_put_contents("c:/temp/a.csv","lskdflsdf");
launch("c:/temp/a.csv");