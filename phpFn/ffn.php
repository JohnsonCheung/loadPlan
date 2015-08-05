<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-29
 * Time: 16:51
 */
function ffn_dlt($ffn)
{
    if (file_exists($ffn)) {
        unlink($ffn);
    }
}