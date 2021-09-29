<?php

//количество фотографий для фотокниги в заказе
function SelectPhotoBookCount($order_id) {
	global $order_book_select_db_name;
	
	$query = "SELECT `photo_id` FROM $order_book_select_db_name WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	return ($n);
}


?>