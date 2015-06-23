<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 9/6/2015
 * Time: 20:09
 */

namespace mysqlDb;
class Db {
    private $con;
    function __construct($dbNm) {
        $this->con = db_con($dbNm);
    }
    function tblAy() {

    }
} 