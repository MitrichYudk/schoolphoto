<?php

$query = "SELECT `id` FROM $gal_size_db_name WHERE `gal_id`='$gal_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$n = mysql_num_rows($res);

for ($i=0; $i<$n; $i++) {
	$row = mysql_fetch_array($res);
	$size_id = $row['id'];
	$this_count = intval($_POST["size_count_$size_id"]);

	if($this_count) {
		$query_sel = "SELECT `id` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id' AND `size_id`='$size_id'";
		@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_sel = mysql_fetch_array($res_sel);
		$sel_id = $row_sel['id'];

		if ($sel_id) {
			$query_up = "UPDATE $select_db_name SET `count`='$this_count' WHERE `id`='$sel_id'";
			@$res_up = mysql_query($query_up) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

		} else {
			$query_ins = "INSERT INTO $select_db_name VALUES('', '$user_id', '$gal_id', '$photo_id', '$size_id', '$this_count')";
			@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");
		}
	}

}

Header("HTTP/1.1 301 Permanent Redirect");
Header("Location: index.php?id=$gal_id&page=$page#a$photo_id");

?>