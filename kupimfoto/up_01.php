<?php
include "config.php";

//таблица для заказов
echo "<li><font color=green>Update $gal_db_name</font>";
$query = "ALTER TABLE $gal_db_name ADD `stat` BIGINT NOT NULL AFTER `book_count`";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}




?>