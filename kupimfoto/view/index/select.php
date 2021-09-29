<?php

$tpl_data['title'] = "Выбираем размеры для фотографии";
$tpl_data['style_display'] = 'none';

$tpl_data['data'] .= "<p><center><form action='index.php?action=select_1&id=$gal_id&photo_id=$photo_id&page=$page' method='post'><table align=center><tr valign=top>";

$query = "SELECT `src_min`, `src_big` FROM $photo_db_name WHERE `id`='$photo_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$row = mysql_fetch_array($res);
$photo_src_min = $row['src_min'];
$photo_src_big = $row['src_big'];

$tpl_data['data'] .= "<td rowspan=500><a class='fancybox' rel='group' href='$photo_src_big' title='Увеличить'><img src='$photo_src_min'></a></td>";

$query = "SELECT `id`, `title`, `price` FROM $gal_size_db_name WHERE `gal_id`='$gal_id' ORDER BY `id` DESC";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$n = mysql_num_rows($res);

$tpl_data['data'] .= "<tr align=center valign=top><td>Размер</td><td>Цена</td><td>Количество</td></tr>";
for ($i=0; $i<$n; $i++) {
	$row = mysql_fetch_array($res);
	$size_id = $row['id'];
	$size_title = $row['title'];
	$size_price = $row['price'];
	
	$query_sel = "SELECT `count` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id' AND `size_id`='$size_id'";
	@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row_sel = mysql_fetch_array($res_sel);
	$count_sel = $row_sel['count'];

	$tpl_data['data'] .= "<tr align=center valign=top><td height='10'>$size_title</td><td>$size_price р.</td><td><input type=text autocomplete='off' value='$count_sel' name='size_count_$size_id' size=6></td></tr>";


}


$tpl_data['data'] .= "<tr><td colspan='3' align='center'><input type=submit NAME=submit value='Выбрать'>";
$tpl_data['data'] .= '</tr></table></form>';


//сумма заказа
$query_sel = "SELECT `id`, `size_id`, `count` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id'";
@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
$n_sel = mysql_num_rows($res_sel);
if ($n_sel) {
	for ($sel=0; $sel<$n_sel; $sel++) {
		$row_sel = mysql_fetch_array($res_sel);
		$sel_id = $row_sel['id'];
		$size_id = $row_sel['size_id'];
		$count_sel = $row_sel['count'];
		
		$query_size = "SELECT `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
		@$res_size = mysql_query($query_size) or die("<li>Err: $query_size<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_size = mysql_fetch_array($res_size);
		$size_price = $row_size['price'];
	
		$summary_price = $summary_price + $count_sel * $size_price;
	}			
}

$tpl_data['price'] .= "<nobr>Сумма заказа: $summary_price р.</nobr>";


?>