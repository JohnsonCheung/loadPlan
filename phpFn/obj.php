<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2015-07-29
 * Time: 11:22
 */
function obj_setPrp($obj, array $ay) {
    foreach($ay as $k=>$v) {
        $obj->$k = $v;
    }
}