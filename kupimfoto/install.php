<?php
include "config.php";

/*
//таблица для стандартных размеров
echo "<li><font color=green>Install $size_db_name</font>";
mysql_query("drop table $size_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $size_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`price` INT NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}


//таблица для размеров галерей
echo "<li><font color=green>Install $gal_size_db_name</font>";
mysql_query("drop table $gal_size_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $gal_size_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`gal_id` BIGINT NOT NULL,
`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`price` INT NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

//таблица для фотографий
echo "<li><font color=green>Install $photo_db_name</font>";
mysql_query("drop table $photo_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $photo_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`gal_id` BIGINT NOT NULL,
`file_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`src_min` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`src_big` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}


//таблица для выбора
echo "<li><font color=green>Install $select_db_name</font>";
mysql_query("drop table $select_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $select_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`user_id` BIGINT,
`gal_id` BIGINT,
`photo_id` BIGINT,
`size_id` BIGINT,
`count` INT,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

//таблица для выбора фотокниги
echo "<li><font color=green>Install $book_select_db_name</font>";
mysql_query("drop table $book_select_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $book_select_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`user_id` BIGINT,
`gal_id` BIGINT,
`photo_id` BIGINT,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

//таблица для заказа фотокниги
echo "<li><font color=green>Install $order_book_select_db_name</font>";
mysql_query("drop table $order_book_select_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $order_book_select_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`order_id` BIGINT,
`photo_id` BIGINT,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

//таблица для покупателей
echo "<li><font color=green>Install $user_db_name</font>";
mysql_query("drop table $user_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $user_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`family` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`otch` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`phone` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

//таблица для галерей
echo "<li><font color=green>Install $gal_db_name</font>";
mysql_query("drop table $gal_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $gal_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`pass` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`show_desc` TEXT,
`hide_desc` TEXT,
`search_index` TEXT,
`book_count` INT NOT NULL,
`stat` BIGINT NOT NULL,
`status` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}

*/



//таблица для заказов
echo "<li><font color=green>Install $order_db_name</font>";
mysql_query("drop table $order_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $order_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`user_id` BIGINT,
`gal_id` BIGINT,
`date` INT,
`price` INT,
`comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`status` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`gal_status` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}


//таблица для фотографий заказа
echo "<li><font color=green>Install $order_select_db_name</font>";
mysql_query("drop table $order_select_db_name"); //если таблица была - уничтожаем
$query = "CREATE TABLE $order_select_db_name (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`order_id` BIGINT,
`gal_id` BIGINT,
`photo_id` BIGINT,
`size_id` BIGINT,
`count` INT,
`status` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
INDEX ( `id` ) )";
$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
if ($res) {echo "<br>Ok";}




?>