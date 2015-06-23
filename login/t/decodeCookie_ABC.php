<?php
require_once "/../../phpFn/cipher.php";
var_dump($_COOKIE);
$a = @$_COOKIE['a'];
$b = @$_COOKIE['b'];
$c = @$_COOKIE['c'];
$a1 = decrypt($a);
$b1 = decrypt($b);
$c1 = decrypt($c);
var_dump($a1);
var_dump($b1);
var_dump($c1);
?>