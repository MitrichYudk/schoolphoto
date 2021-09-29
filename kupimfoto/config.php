<?php
set_time_limit(3000);
ini_set('max_file_uploads', "1000"); 
include "view/lib.php";

//серверные переменные
//$server="localhost"; $db_user="root"; $db_pass=""; $database="kupimfoto";
$server='mysql76.1gb.ru'; $db_user='gb_schoolfoto'; $db_pass='51d20e2012'; $database='gb_schoolfoto';


//
$admin_data['username'] = 'Yuriy';
$admin_data['password'] = 'Kozlov';
$admin_data['email'] = 'yudk@mail.ru';
$admin_data['my_email'] = 'yakushenko-galina@mail.ru';

//галерей на страницу в админке
$gal_per_page = 25;
//столбцов фотографий в админке
$cols_per_page = 3;
//фотографий на страницу в админке
$photo_per_page = 99;
//рамеры миниатюр
$min_size = 200;

//столбцов фотографий для покупателя
$show_cols_per_page = 3;
//фотографий на страницу для покупателя
$show_photo_per_page = 54;


//заказов на страницу в админке
$order_per_page = 100;
//галерей заказов на страницу в админке
$order_gal_per_page = 10;


//текст письма
$mail_tpl_head = "Спасибо за Ваш заказ. Вы выбрали: ";

$mail_tpl_footer = "
С уважением,
Калининградский центр школьной фотографии
ООО 'Школьное фото'.
Производство фотокниг.
г. Калининград,
тел. 8-(4012)-39-00-67
моб. 8-911-459-00-67
http://школьноефото.рф
http://photo-kaliningrad.ru
";



//директория со скриптом
$script_dir = 'kupimfoto';
$domain_name = 'http://photo-kaliningrad.ru';
$images_dir = 'images';

$db_prefix = 'kupimfoto_';

$size_db_name = $db_prefix.'size';
$gal_db_name = $db_prefix.'gal';
$gal_size_db_name = $db_prefix.'gal_size';
$photo_db_name = $db_prefix.'photo';
$user_db_name = $db_prefix.'user';
$select_db_name = $db_prefix.'select';
$order_db_name = $db_prefix.'order';
$order_select_db_name = $db_prefix.'order_select';
$book_select_db_name = $db_prefix.'book_select';
$order_book_select_db_name = $db_prefix.'order_book_select';





mysql_connect($server, $db_user, $db_pass) or die("Не могу соединиться с базой данных<br>".mysql_errno()." : ".mysql_error()."<br>");
mysql_select_db($database) or die("Не могу выбрать нужную базу данных<br>".mysql_errno()." : ".mysql_error()."<br>");
mysql_query("SET NAMES UTF8");


function create_today_image_src ()
{
	global $script_dir, $images_dir, $domain_name;
	$today_image_src = $domain_name.'/'.$script_dir.'/'.$images_dir.'/'.date('Y').'/';
    $today_image_src = $today_image_src.date('m').'/';
    $today_image_src = $today_image_src.date('d').'/';

    return $today_image_src;
}

function create_today_image_dir ()
{
	//создаем структуру директорий, в которую будут сегодня заливаться картинки
	global $script_dir, $images_dir;
	$today_image_dir = $_SERVER['DOCUMENT_ROOT'].'/'.$script_dir.'/'.$images_dir.'/'.date('Y').'/';
	if (!file_exists($today_image_dir)) {mkdir($today_image_dir);};
    $today_image_dir=$today_image_dir.date('m').'/';
    if (!file_exists($today_image_dir)) {mkdir($today_image_dir);};
    $today_image_dir=$today_image_dir.date('d').'/';
    if (!file_exists($today_image_dir)) {mkdir($today_image_dir);};

    return $today_image_dir;
}
 

?>