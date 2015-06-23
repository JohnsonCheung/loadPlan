<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/6/2015
 * Time: 18:47
 */
if (!isset($_SERVER['HTTP_HOST']))
    exit();

include_once 'updFn.php';
logg($HTTP_RAW_POST_DATA);
$regDro = json_decode($HTTP_RAW_POST_DATA);
//logg($regDro);
upd($regDro);
?>

