<?php
require_once "/../../phpFn/cipher.php";
$a = @$_COOKIE['a'];
$b = @$_COOKIE['b'];
$c = @$_COOKIE['c'];
$usrId = decrypt($a);
$sessId = decrypt($b);
$actId = decrypt($c);

