<?php


$family = htmlspecialchars($_POST['family']);
$name = htmlspecialchars($_POST['name']);
$otch = htmlspecialchars($_POST['otch']);
$phone = htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);
$comment = htmlspecialchars($_POST['comment']);

$mail_tpl_print = "\r\nДля печати:\r\n";

$query_up = "UPDATE $user_db_name SET `family`='$family', `name`='$name', `otch`='$otch', `phone`='$phone', `email`='$email' WHERE `id`='$user_id'";
@$res_up = mysql_query($query_up) or die("<li>Error: $query_up<br>".mysql_errno()." : ".mysql_error()."<br>");

$query_ins = "INSERT INTO $order_db_name VALUES('', '$user_id', '$gal_id', '$timestamp', '', '$comment', 'on', 'on')";
@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");
$order_id = mysql_insert_id();

$query_sel = "SELECT `photo_id` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' GROUP BY `photo_id`";
@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
$n_sel = mysql_num_rows($res_sel);

for ($sel=0; $sel<$n_sel; $sel++) {
	$row_sel = mysql_fetch_array($res_sel);
	$photo_id = $row_sel['photo_id'];

	$query_t = "SELECT `file_name` FROM $photo_db_name WHERE `id`='$photo_id'";
	@$res_t = mysql_query($query_t) or die("<li>Err: $query_t<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row_t = mysql_fetch_array($res_t);
	$file_name = $row_t['file_name'];
	$mail_tpl_print .= "Фотография $file_name:\r\n";

	$query_order = "SELECT `size_id`, `count` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id'";
	@$res_order = mysql_query($query_order) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
	$size_n = mysql_num_rows($res_order);
	for ($size_i=0; $size_i<$size_n; $size_i++) {
		$row_order = mysql_fetch_array($res_order);
		$size_id = $row_order['size_id'];
		$count = $row_order['count'];

		if ($count) {
			$query_ins = "INSERT INTO $order_select_db_name VALUES('', '$order_id', '$gal_id', '$photo_id', '$size_id', '$count','on')";
			@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");
			
			$query_size = "SELECT `title`, `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
			@$res_size = mysql_query($query_size) or die("<li>Err: $query_size<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_size = mysql_fetch_array($res_size);
			$size_title = $row_size['title'];
			$size_price = $row_size['price'];
		
			$size_summary_price = $count * $size_price;
			$summary_price = $summary_price + $size_summary_price;

			$mail_tpl_print .= "Размер $size_title: $count шт. по $size_price р. = $size_summary_price р.;\r\n";

		}

	}
}


$mail_tpl_print .= "Итого: $summary_price р.";
$query_up = "UPDATE $order_db_name SET `price`='$summary_price' WHERE `id`='$order_id'";
@$res_up = mysql_query($query_up) or die("<li>Error: $query_up<br>".mysql_errno()." : ".mysql_error()."<br>");




//стираем его выбор
$query = "DELETE FROM $select_db_name WHERE `user_id`='$user_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");




//фотокнига
$query_sel = "SELECT `photo_id` FROM $book_select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id'";
@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
$n_sel = mysql_num_rows($res_sel);

if ($n_sel) {
	$mail_tpl_book = "\r\n\r\nДля фотокниги: ";
}

for ($sel=0; $sel<$n_sel; $sel++) {
	$row_sel = mysql_fetch_array($res_sel);
	$photo_id = $row_sel['photo_id'];

	$query_ins = "INSERT INTO $order_book_select_db_name VALUES('', '$order_id', '$photo_id')";
	@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query_t = "SELECT `file_name` FROM $photo_db_name WHERE `id`='$photo_id'";
	@$res_t = mysql_query($query_t) or die("<li>Err: $query_t<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row_t = mysql_fetch_array($res_t);
	$file_name = $row_t['file_name'];
	$mail_tpl_book .= "$file_name; ";


}

//стираем его выбор
$query = "DELETE FROM $book_select_db_name WHERE `user_id`='$user_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");



$mail_tpl = $mail_tpl_head.$mail_tpl_print.$mail_tpl_book."\r\n$comment\r\n".$mail_tpl_footer;


//посылаем ему
mail("$email, ".$admin_data['email'], "Ваш заказ в ООО 'Школьное фото'", $mail_tpl, 'From: Юрий <yudk@mail.ru>'); 

//посылаем админу
mail($admin_data['email'], "Новый заказ", $mail_tpl, 'From: Юрий <admin@школьное-фото.рф>'); 
mail($admin_data['my_email'], "Новый заказ", $mail_tpl, 'From: Юрий <admin@школьное-фото.рф>'); 


//добавляем нового
$query = "INSERT INTO $user_db_name VALUES('', '', '', '', '', '')";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$user_id = mysql_insert_id();
SetCookie('p5_user', $user_id);


Header("HTTP/1.1 301 Permanent Redirect");
Header("Location: index.php?id=$gal_id");

?>