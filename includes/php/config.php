<?php
@ob_start();
@session_start();
session_cache_expire(0);
error_reporting(E_ALL ^ E_NOTICE);

define('_hostName','localhost');	
/*define('_useName','root');	
define('_dbName','provision');	
define('_pass','');
define('myWeb','http://localhost/provision/');
define('myPath','../images/upload/');
define('webPath','http://localhost/provision/images/upload/');
define('def_icon','ico1.png');*/
define('_useName','tung');	
define('_dbName','ongnuoc_db');	
define('_pass','tung');
//define('_useName','ongnuoc_db');	
//define('_dbName','ongnuoc_db');	
//define('_pass','671977');
define('myWeb','/');
define('myPath','../images/upload/');
define('webPath','/images/upload/');
define('def_icon','ico1.png');

include_once('mysql.php');
include_once("commonFunction.php");
include_once("resize.php");
include_once("class.search.php");
include_once("bootPagination.php");

global $db;
$db = new CDB_MySql();
$db->connect(_hostName,_useName,_pass); 
$db->selectdb(_dbName);
mysql_query("SET NAMES utf8");

?>
