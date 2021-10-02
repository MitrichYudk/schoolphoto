<?php

$tpl_data['title'] = "Галерея №$gal_id / $gal_title /";
$tpl_data['body_title'] = "$gal_title";
$tpl_data['desc'] = $gal_show_desc;

//можно-ли сохранять ПКМ
$query = "SELECT `is_save` FROM $gal_db_name WHERE `id`='$gal_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$row = mysql_fetch_array($res);
$is_save = $row['is_save'];

if ($is_save == 'on') {
	$tpl_data['is_save'] = file('is_save.js');
} else {
	$tpl_data['is_save'] = '';
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

$is_check_disabled = false;
if ($this_book_count >= $book_count) {
	$is_check_disabled = true;
}

//выводим страницы
$tpl_data['pages'] .= 'стр.: ';
$query = "SELECT `id` FROM $photo_db_name WHERE `gal_id`='$gal_id'";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$n = mysql_num_rows($res);

$page_count = intval($n / $show_photo_per_page);
for ($i=1; $i<=$page_count+1;$i++){
	if ($page != $i) {
		$tpl_data['pages'] .= " <a href=?id=$gal_id&page=".$i.">".$i."</a> ";
	}
	else
	{
		$tpl_data['pages'] .= "<b>[".$i."]</b>";
	}
}
$limit_n = ($page - 1) * $show_photo_per_page;

$query = "SELECT `id`, `file_name`, `src_big`, `src_min` FROM $photo_db_name WHERE `gal_id`='$gal_id' LIMIT $limit_n, $show_photo_per_page";
@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
$n = mysql_num_rows($res);
$rows_per_page = $n / $show_cols_per_page;

$tpl_data['data'] .= '<table align=center>';

for ($i=0; $i<$rows_per_page; $i++) {
	$tpl_data['data'] .= "<tr align=center valign=middle>";
	for ($j=0; $j<$show_cols_per_page; $j++) {
		$row = mysql_fetch_array($res);
		$photo_id = $row['id'];
		$photo_file_name = $row['file_name'];
		$photo_src_min = $row['src_min'];
		$photo_src_big = $row['src_big'];
		
		if ($photo_id) {

			$select_line = '';
			$query_sel = "SELECT `id`, `size_id`, `count` FROM $select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id'";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
			$n_sel = mysql_num_rows($res_sel);
			if ($n_sel) {
				for ($sel=0; $sel<$n_sel; $sel++) {
					$row_sel = mysql_fetch_array($res_sel);
					$sel_id = $row_sel['id'];
					$size_id = $row_sel['size_id'];
					$count_sel = $row_sel['count'];
					
					if ($count_sel) {
						$query_size = "SELECT `title`, `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
						@$res_size = mysql_query($query_size) or die("<li>Err: $query_size<br>".mysql_errno()." : ".mysql_error()."<br>");
						$row_size = mysql_fetch_array($res_size);
						$size_title = $row_size['title'];
						$size_price = $row_size['price'];
					
						$select_line .= "$size_title: $count_sel шт; <a href='?id=$gal_id&action=del_sel&sel_id=$sel_id&page=$page' title='Удалить'><img src='data/del.png' border='0'></a><br>";

					}
				}			
			}

//фотокнига
			$is_checked = '';
			$query_book = "SELECT `id` FROM $book_select_db_name WHERE `user_id`='$user_id' AND `gal_id`='$gal_id' AND `photo_id`='$photo_id'";
			@$res_book = mysql_query($query_book) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
			$n_book = mysql_num_rows($res_book);
			if ($n_book) {
				$is_checked = 'checked';
			}
			if ($is_check_disabled) {
				if ($is_checked) {
					$is_disabled_show = '';
				} else {
					$is_disabled_show = 'disabled';
				}
			}



			$tpl_data['data'] .= "<td width='210' height='210'><a id='example4' name='a$photo_id' rel='group' href='$photo_src_big' title='Увеличить'><img src='$photo_src_min'></a><br>$photo_file_name</td>
			<td valign=top>Выбрано:<br>$select_line
			<a href='?id=$gal_id&action=select&photo_id=$photo_id&page=$page' title='Выбрать'>Выбрать еще</a><br><br>
			<label><input type='checkbox' class='ph_book' id='ph_book-$photo_id' $is_checked $is_disabled_show>Для фотокниги</label>


			</td>

			";
		} else {
			$tpl_data['data'] .= "<td width='210' height='210'></td><td></td>";

		}

	}
	$tpl_data['data'] .= "</tr>";
}
$tpl_data['data'] .= "</table>";


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

$tpl_data['price'] .= "Сумма вашего заказа: $summary_price р.";

if ($this_book_count || $summary_price) {
	$tpl_data['style_display'] = '';
} else {
	$tpl_data['style_display'] = 'none';
}


?>

