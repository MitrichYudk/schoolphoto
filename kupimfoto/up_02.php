<?php
include "config.php";

/*
//таблица для заказов
echo "<li><font color=green>Update $gal_db_name</font>";
$query = "ALTER TABLE $gal_db_name ADD `is_save` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `status`";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

$query = "ALTER TABLE $gal_db_name ADD `end_date` INT NOT NULL AFTER `is_save`";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

$end_date = time() + 86400 * 30;
$query = "UPDATE $gal_db_name SET `end_date`='$end_date'";
@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}
*/

$query = "UPDATE $gal_db_name SET `is_save`='on'";
@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}




?>