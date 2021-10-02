<?php
	include 'config.php';

	$user_id = intval($_COOKIE['p5_user']);
	$gal_id = intval($_GET['gal_id']);
	

	//сколько для фотокниги всего он уже выбрал
	$query = "SELECT `id` FROM $book_select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$this_book_count = mysql_num_rows($res);

	//сколько для печати
	$query = "SELECT `id` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$this_print_count = mysql_num_rows($res);

	if ($this_book_count || $this_print_count) {
		echo '1';
	} else {
		echo '0';
	}

?>

