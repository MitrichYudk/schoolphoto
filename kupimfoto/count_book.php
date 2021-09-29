<?php
	include 'config.php';

	$photo_id = $_GET['photo_id'];
	list($t,$photo_id) = explode('-', $photo_id);
	
	$user_id = intval($_COOKIE['p5_user']);
	$gal_id = intval($_GET['gal_id']);
	$action = $_GET['action'];
	
	if ($action == 'del') {
		$query = "DELETE FROM $book_select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id'";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	} 

	if ($action == 'add') {
		$query_ins = "INSERT INTO $book_select_db_name VALUES('', '$user_id', '$gal_id', '$photo_id')";
		@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");
	}

	//сколько всего он уже выбрал
	$query = "SELECT `id` FROM $book_select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$this_book_count = mysql_num_rows($res);

	//сколько можно
	$query = "SELECT `book_count` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$book_count = $row['book_count'];

	if ($this_book_count >= $book_count) {
		echo '1';
	} else {
		echo '0';
	}

?>

