<?php

$mode = $_GET['mode'];
if (!isset($mode)) {
	$mode = 'classic';
}

if ($action == 'show') {

	$sort = $_GET['sort'];
	if (!isset($sort)) {
		$sort = 'id';
	}
	
	$num = $_GET['num'];
	if (!isset($num)) {
		$num = 'desc';
	}

	$tpl_data['title'] = 'Заказы';

	$tpl_data['menu'] = '
	<li class="item-184">Заказы</li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	if ($mode == 'classic') {
		$tpl_data['data'] .= "<p>Режим отображения: <b>По заказам</b> | <a href='?obj=order&action=show&mode=photo'>По фотографиям</a> | <a href='?obj=order&action=show&mode=gals'>По галереям</a>";
	} 
	if ($mode == 'photo') {
		$tpl_data['data'] .= "<p>Режим отображения: <a href='?obj=order&action=show&mode=classic'>По заказам</a> | <b>По фотографиям</b> | <a href='?obj=order&action=show&mode=gals'>По галереям</a>";
	}
	if ($mode == 'gals') {
		$tpl_data['data'] .= "<p>Режим отображения: <a href='?obj=order&action=show&mode=classic'>По заказам</a> | <a href='?obj=order&action=show&mode=photo'>По фотографиям</a> | <b>По галереям</b>";
	}



	if ($mode == 'classic') {

		$tpl_data['data'] .= '<p>стр: ';

		//выводим страницы
		$query = "SELECT `id` FROM $order_db_name";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		$page_count = intval($n / $gal_per_page);
		for ($i=1; $i<=$page_count+1;$i++){
			if ($page != $i) {
				$page_line .= " <a href='?obj=order&action=show&page=$i&sort=$sort&num=$num&mode=$mode'>$i</a> ";
			}
			else
			{
				$page_line .= "<b>[".$i."]</b>";
			}
		}
		$limit_n = ($page - 1) * $gal_per_page;


		$tpl_data['data'] .= "$page_line<center><table align=center><tr align=center>

		<td>Номер<br>
		<font size='-1'><nobr>
		<a href='?obj=order&action=show&sort=id&num=desc'>боль.</a>
		<a href='?obj=order&action=show&sort=id&num=asc'>мень.</a>
		</nobr></td>
		<td>Статус</td>
		<td>Дата<br>
		<font size='-1'><nobr>
		<a href='?obj=order&action=show&sort=date&num=desc'>рань.</a>
		<a href='?obj=order&action=show&sort=date&num=asc'>позж.</a>
		</nobr></td>
		<td>ФИО</td>
		<td>Сумма<br>
		<font size='-1'><nobr>
		<a href='?obj=order&action=show&sort=price&num=desc'>боль.</a>
		<a href='?obj=order&action=show&sort=price&num=asc'>мень.</a>
		</nobr></td>

		<td>Выбрано:</td></tr>";


		$query = "SELECT `id`, `user_id`, `gal_id`, `date`, `price`, `comment`, `status` FROM $order_db_name ORDER BY `$sort` $num LIMIT $limit_n, $gal_per_page";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		for ($i=0; $i<$n; $i++) {
			$row = mysql_fetch_array($res);
			$order_id = $row['id'];
			$user_id = $row['user_id'];
			$gal_id = $row['gal_id'];
			$price = $row['price'];
			$comment = $row['comment'];
			
			$query_title = "SELECT `title` FROM $gal_db_name WHERE `id`='$gal_id'";
			@$res_title = mysql_query($query_title) or die("<li>Err: $query_title<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_title = mysql_fetch_array($res_title);
			$gal_title = $row_title['title'];

			$query_user = "SELECT `family`, `name`, `otch`, `phone`, `email` FROM $user_db_name WHERE `id`='$user_id'";
			@$res_user = mysql_query($query_user) or die("<li>Err: $query_user<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_user = mysql_fetch_array($res_user);
			$order_user = $row_user['family'].' '.$row_user['name'].' '.$row_user['otch'];
			$order_phone = $row_user['phone'];
			$order_email = $row_user['email'];

			$order_date = date('j-m-Y', $row['date']);
			$status = $row['status'];

			if ($status == 'on') {
				$status_line = "<a name='a$order_id' href=?obj=order&action=off&order_id=$order_id&mode=$mode&page=$page><img src='data/order_on.png' border='0'></a>";
			}
			if ($status == 'off') {
				$status_line = "<a name='a$order_id' href=?obj=order&action=on&order_id=$order_id&mode=$mode&page=$page><img src='data/order_off.png' border='0'></a>";
			}
			$status_line .= "&nbsp;<a name='a$order_id' href='?obj=order&action=del&order_id=$order_id' title='Удалить'><img src='data/del.png' border='0'></a>";

			//если есть в фотокниге заказ, то его отображаем, если нет - нет
			if (SelectPhotoBookCount($order_id)) {
				$is_photobook_line = "<br><br><a href=?obj=order&action=book&order_id=$order_id&mode=$mode&page=$page><nobr>Для фотокниги</nobr></a>";
			} else {
				$is_photobook_line = "";
			}


			$tpl_data['data'] .= "<tr align=center valign=top><td>#$order_id<br>$gal_title</td><td>$status_line</td><td>$order_date</td><td>$order_user<br>$order_phone<br>$order_email</td><td><nobr>$price р.</nobr></td><td>
			<a href=?obj=order&action=print&order_id=$order_id&mode=$mode&page=$page>Для печати</a>
			$is_photobook_line</td></tr>
			<tr align=center valign=top><td colspan=6>$comment</td></tr>
			";


		}

		$tpl_data['data'] .= '</table>';
		$tpl_data['data'] .= "<p align='left'>$page_line";
	}














	if ($mode == 'photo') {

		$tpl_data['data'] .= '<p>стр: ';

		//выводим страницы
		$query = "SELECT `id` FROM $order_db_name WHERE `status`='on' AND `gal_status`='on' GROUP BY `gal_id`";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		$page_count = intval($n / $order_gal_per_page);
		for ($i=1; $i<=$page_count+1;$i++){
			if ($page != $i) {
				$tpl_data['data'] .= " <a href='?obj=order&action=show&page=$i&sort=$sort&num=$num&mode=$mode'>$i</a> ";
			}
			else
			{
				$tpl_data['data'] .= "<b>[".$i."]</b>";
			}
		}
		$limit_n = ($page - 1) * $order_gal_per_page;


		$tpl_data['data'] .= "<center><table align=center><tr align=center>

		<td>Фотография</td>
		<td>Размер</td>
		</tr>";

		$query = "SELECT `id`, `user_id`, `gal_id` FROM $order_db_name WHERE `status`='on' AND `gal_status`='on'  GROUP BY `gal_id` LIMIT $limit_n, $order_gal_per_page";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		for ($i=0; $i<$n; $i++) {
			$row = mysql_fetch_array($res);
			$order_id = $row['id'];
			$user_id = $row['user_id'];
			$gal_id = $row['gal_id'];

			$gal_price = 0;

			//заголовок
			$query_sel = "SELECT `title`, `show_desc`, `hide_desc` FROM $gal_db_name WHERE `id`='$gal_id'";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_sel = mysql_fetch_array($res_sel);
			$title = $row_sel['title'];
			$show_desc = $row_sel['show_desc'];
			$hide_desc = $row_sel['hide_desc'];

			$tpl_data['data'] .= "<tr><td colspan=3><h5>$title</h5></td></tr>";

			//фотографии
			$query_sel = "SELECT `photo_id` FROM $order_select_db_name WHERE `gal_id`='$gal_id' AND `status`='on' GROUP BY `photo_id`";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
			$n_sel = mysql_num_rows($res_sel);
			for ($sel=0; $sel<$n_sel; $sel++) {
				$row_sel = mysql_fetch_array($res_sel);
				$photo_id = $row_sel['photo_id'];

				$query_photo = "SELECT `file_name`, `src_min` FROM $photo_db_name WHERE `id`='$photo_id'";
				@$res_photo = mysql_query($query_photo) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
				$row_photo = mysql_fetch_array($res_photo);
				$photo_file_name = $row_photo['file_name'];
				$photo_src_min = $row_photo['src_min'];

				$tpl_data['data'] .= "<tr align=center><td width='210' height='210' valign=top><img src='$photo_src_min'><br>$photo_file_name</td>";

				//размеры
				$tpl_data['data'] .= "<td valign=top align=left><ul>";
				$query_size = "SELECT `size_id` FROM $order_select_db_name WHERE `photo_id`='$photo_id' GROUP BY `size_id`";
				@$res_size = mysql_query($query_size) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
				$n_size = mysql_num_rows($res_size);
				for ($i_size=0; $i_size<$n_size; $i_size++) {
					$row_size = mysql_fetch_array($res_size);
					$size_id = $row_size['size_id'];

					$query_title = "SELECT `title`, `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
					@$res_title = mysql_query($query_title) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
					$row_title = mysql_fetch_array($res_title);
					$size_title = $row_title['title'];
					$size_price = $row_title['price'];

					$tpl_data['data'] .= "<li>$size_title";


					//суммарное количество размера
					$this_count = 0;
					$query_count = "SELECT `count` FROM $order_select_db_name WHERE `photo_id`='$photo_id' AND `size_id`='$size_id'";
					@$res_count = mysql_query($query_count) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
					$n_count = mysql_num_rows($res_count);
					for ($i_count=0; $i_count<$n_count; $i_count++) {
						$row_count = mysql_fetch_array($res_count);
						$count = $row_count['count'];
						$this_count = $this_count + $count;
						$gal_price = $gal_price + $size_price * $count;
					}
					$tpl_data['data'] .= " - $this_count шт. Из них: ";

					//заказы
					$tpl_data['data'] .= "<ul>";
					$query_order = "SELECT `order_id`, `count` FROM $order_select_db_name WHERE `photo_id`='$photo_id' AND `size_id`='$size_id'";
					@$res_order = mysql_query($query_order) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
					$n_order = mysql_num_rows($res_order);
					for ($i_order=0; $i_order<$n_order; $i_order++) {
						$row_order = mysql_fetch_array($res_order);
						$order_id = $row_order['order_id'];
						$count = $row_order['count'];
						$tpl_data['data'] .= "<li><a href=?obj=order&action=print&order_id=$order_id&page=$page>Заказ #$order_id</a> - $count шт.";
					}
					$tpl_data['data'] .= "</ul>";


				}
				$tpl_data['data'] .= "</ul></td>";


			}


			$tpl_data['data'] .= "<tr><td colspan=3><b>Общая стоимость: $gal_price р.</b></td></tr>";

		}

		$tpl_data['data'] .= '</table>';
	}















	if ($mode == 'gals') {

		$tpl_data['data'] .= '<p>стр: ';

		//выводим страницы
		$query = "SELECT `id` FROM $order_db_name WHERE `gal_status`='on' GROUP BY `gal_id`";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		$page_count = intval($n / $order_gal_per_page);
		for ($i=1; $i<=$page_count+1;$i++){
			if ($page != $i) {
				$tpl_data['data'] .= " <a href='?obj=order&action=show&page=$i&sort=$sort&num=$num&mode=$mode'>$i</a> ";
			}
			else
			{
				$tpl_data['data'] .= "<b>[".$i."]</b>";
			}
		}
		$limit_n = ($page - 1) * $order_gal_per_page;


		$tpl_data['data'] .= "<center><table align=center><tr align=center>

		<td>Номер заказа</td>
		<td>Галерея</td>
		<td>Статус</td>
		<td>Дата</td>
		<td>ФИО</td>
		<td>Сумма</td>
		<td>Выбрано</td>
		</tr>";

		$query = "SELECT `gal_id` FROM $order_db_name WHERE `gal_status`='on' GROUP BY `gal_id` LIMIT $limit_n, $order_gal_per_page";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n = mysql_num_rows($res);

		for ($i=0; $i<$n; $i++) {
			$row = mysql_fetch_array($res);
			$gal_id = $row['gal_id'];
	
			//заголовок
			$query_sel = "SELECT `title`, `show_desc`, `hide_desc` FROM $gal_db_name WHERE `id`='$gal_id'";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_sel = mysql_fetch_array($res_sel);
			$title = $row_sel['title'];
			$show_desc = $row_sel['show_desc'];
			$hide_desc = $row_sel['hide_desc'];

			$tpl_data['data'] .= "<tr><td colspan=6><h5>$title</h5></td><td align=center><a href=?obj=order&action=excel&gal_id=$gal_id&page=$page>Excel</a></td></tr>";

			$gal_price = 0;

			$query_order = "SELECT `id`, `user_id`, `date`, `price`, `comment`, `status` FROM $order_db_name WHERE `gal_id`='$gal_id' ORDER BY `id` DESC";
			@$res_order = mysql_query($query_order) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
			$n_order = mysql_num_rows($res_order);
	
			for ($i_order=0; $i_order<$n_order; $i_order++) {
				$row_order = mysql_fetch_array($res_order);
				$order_id = $row_order['id'];
				$user_id = $row_order['user_id'];
				$date = date('j-m-Y', $row_order['date']);
				$price = $row_order['price'];
				$comment = $row_order['comment'];
				$status = $row_order['status'];

				if ($status == 'on') {
					$status_line = "<a name='a$order_id' href=?obj=order&action=off&order_id=$order_id&mode=$mode&page=$page><img src='data/order_on.png' border='0'></a>";
				}
				if ($status == 'off') {
					$status_line = "<a name='a$order_id' href=?obj=order&action=on&order_id=$order_id&mode=$mode&page=$page><img src='data/order_off.png' border='0'></a>";
				}

				$query_user = "SELECT `family`, `name`, `otch`, `phone`, `email` FROM $user_db_name WHERE `id`='$user_id'";
				@$res_user = mysql_query($query_user) or die("<li>Err: $query_user<br>".mysql_errno()." : ".mysql_error()."<br>");
				$row_user = mysql_fetch_array($res_user);
				$order_user = $row_user['family'].' '.$row_user['name'].' '.$row_user['otch'];
				$order_phone = $row_user['phone'];
				$order_email = $row_user['email'];

				$gal_price = $gal_price + $price;


				//если есть в фотокниге заказ, то его отображаем, если нет - нет
				if (SelectPhotoBookCount($order_id)) {
					$is_photobook_line = "<br><a href=?obj=order&action=book&order_id=$order_id&mode=$mode&page=$page><nobr>Для фотокниги</nobr></a>";
				} else {
					$is_photobook_line = "";
				}

			
				$tpl_data['data'] .= "<tr><td>$order_id</td><td>$title</td><td align=center>$status_line</td><td>$date</td><td>$order_user<br>$order_phone<br>$order_email</td><td><nobr>$price р.</nobr></td><td><a href=?obj=order&action=print&order_id=$order_id&mode=$mode&page=$page>Для печати</a>$is_photobook_line</td></tr><tr align=left><td colspan=7><i>$comment</i></td></tr>";

			}


			$tpl_data['data'] .= "<tr><td colspan=6 align=right><b>Итого по `$title`:</b></td><td><b>$gal_price р.</td></tr>";

		}

		$tpl_data['data'] .= '</table>';
	}




}


if ($action == 'on') {

	$query = "UPDATE $order_db_name SET `status`='on' WHERE `id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "UPDATE $order_select_db_name SET `status`='on' WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=order&action=show&mode=$mode&page=$page#a$order_id");

}

if ($action == 'off') {

	$query = "UPDATE $order_db_name SET `status`='off' WHERE `id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "UPDATE $order_select_db_name SET `status`='off' WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=order&action=show&mode=$mode&page=$page#a$order_id");

}

if ($action == 'del') {

	$tpl_data['title'] = 'Удаляем заказ';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] .= "<center><h2>Вы уверены?</h2>
	<a href='?obj=order&action=del_1&order_id=$order_id' title='Удалить'>Да</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href='admin.php?obj=order&action=show'><font size=+3>Нет</font></a>
	";

}


if ($action == 'del_1') {

	$query = "DELETE FROM $order_db_name WHERE `id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "DELETE FROM $order_select_db_name WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	//следующий заказ
	$query = "SELECT `id` FROM $order_db_name WHERE `id`>'$order_id' ORDER BY `id` ASC";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$new_order_id = $row['id'];

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=order&action=show&mode=$mode&page=$page#a$new_order_id");
}



if ($action == 'print') {

	$query = "SELECT `gal_id` FROM $order_select_db_name WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$gal_id = $row['gal_id'];

	$query = "SELECT `title` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$title = $row['title'];

	$tpl_data['data'] .= "<h3>$title</h3>";
	
	$query = "SELECT `user_id` FROM $order_db_name WHERE `id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$user_id = $row['user_id'];

	$query = "SELECT `family`, `name`, `otch` FROM $user_db_name WHERE `id`='$user_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$family = $row['family'];
	$name = $row['name'];
	$otch = $row['otch'];

	$tpl_data['data'] .= "<h4>$family $name $otch</h4>";


	$tpl_data['data'] .= '<center><table align=center>';

	$query = "SELECT `photo_id` FROM $order_select_db_name WHERE `order_id`='$order_id' GROUP BY `photo_id`";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($sel=0; $sel<$n; $sel++) {
		$row = mysql_fetch_array($res);
		$photo_id = $row['photo_id'];

		$query_sel = "SELECT `file_name`, `src_min` FROM $photo_db_name WHERE `id`='$photo_id'";
		@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_sel = mysql_fetch_array($res_sel);
		$photo_file_name = $row_sel['file_name'];
		$photo_src_min = $row_sel['src_min'];

		$tpl_data['data'] .= "<tr align=center><td width='210' height='210' valign=middle><img src='$photo_src_min'><br>$photo_file_name</td><td valign=top>";

		$query_order = "SELECT `id`, `size_id`, `count`, `status` FROM $order_select_db_name WHERE `order_id`='$order_id' AND `photo_id`='$photo_id'";
		@$res_order = mysql_query($query_order) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
		$size_n = mysql_num_rows($res_order);
		for ($size_i=0; $size_i<$size_n; $size_i++) {
			$row_order = mysql_fetch_array($res_order);
			$select_id = $row_order['id'];
			$size_id = $row_order['size_id'];
			$count = $row_order['count'];
			$status = $row_order['status'];

			$query_sel = "SELECT `title` FROM $gal_size_db_name WHERE `id`='$size_id'";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_sel = mysql_fetch_array($res_sel);
			$size_title = $row_sel['title'];

			if ($status == 'on') {
				$status_line = "<a name='a$select_id' href=?obj=order&action=size_off&order_id=$order_id&select_id=$select_id&mode=$mode&page=$page><img src='data/order_on.png' border='0'></a>";
			}
			if ($status == 'off') {
				$status_line = "<a name='a$select_id' href=?obj=order&action=size_on&order_id=$order_id&select_id=$select_id&mode=$mode&page=$page><img src='data/order_off.png' border='0'></a>";
			}			



			$tpl_data['data'] .= "<nobr>$size_title - $count шт. $status_line</nobr><br>";
		}
		$tpl_data['data'] .= "</td></tr>";

	}
	
	$tpl_data['data'] .= '</table>';
	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

}


if ($action == 'size_on') {

	$query = "UPDATE $order_select_db_name SET `status`='on' WHERE `id`='$select_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=order&action=print&order_id=$order_id&mode=$mode&page=$page#a$select_id");

}

if ($action == 'size_off') {

	$query = "UPDATE $order_select_db_name SET `status`='off' WHERE `id`='$select_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	//все ли фотки напечатаны
	$query = "SELECT `id` FROM $order_select_db_name WHERE `order_id`=$order_id AND `status`='on'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	if ($n == 0) {
		$query = "UPDATE $order_db_name SET `status`='off' WHERE `id`='$order_id'";
		@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	}


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=order&action=print&order_id=$order_id&mode=$mode&page=$page#a$select_id");

}




if ($action == 'book') {

	$tpl_data['data'] .= '<center><table align=center>';

	$query = "SELECT `photo_id` FROM $order_book_select_db_name WHERE `order_id`='$order_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($sel=0; $sel<$n; $sel++) {
		$row = mysql_fetch_array($res);
		$photo_id = $row['photo_id'];

		$query_sel = "SELECT `file_name`, `src_min` FROM $photo_db_name WHERE `id`='$photo_id'";
		@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_sel = mysql_fetch_array($res_sel);
		$photo_file_name = $row_sel['file_name'];
		$photo_src_min = $row_sel['src_min'];

		$tpl_data['data'] .= "<tr align=center><td width='210' height='210' valign=middle><img src='$photo_src_min'><br>$photo_file_name</td><td valign=top>";

		$tpl_data['data'] .= "</td></tr>";

	}

	
	$tpl_data['data'] .= '</table>';
	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

}





if ($action == 'excel') {

	$query = "SELECT `title` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$title = $row['title'];

	// Подключаем класс для работы с excel
	require_once('PHPExcel/PHPExcel.php');
	// Подключаем класс для вывода данных в формате excel
	require_once('PHPExcel/PHPExcel/Writer/Excel5.php');

	// Создаем объект класса PHPExcel
	$xls = new PHPExcel();
	// Устанавливаем индекс активного листа
	$xls->setActiveSheetIndex(0);
	// Получаем активный лист
	$sheet = $xls->getActiveSheet();
	// Подписываем лист
	$sheet->setTitle("Заказ");

	// Вставляем текст в ячейку A1
	$sheet->setCellValue("A1", $title);
	$sheet->getStyle('A1')->getFill()->setFillType(
	    PHPExcel_Style_Fill::FILL_SOLID);
	$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
	// Объединяем ячейки
	$sheet->mergeCells('A1:F1');
	// Выравнивание текста
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(
	    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$next_cell = 3;

	$query_order = "SELECT `id`, `user_id`, `price` FROM $order_db_name WHERE `gal_id`='$gal_id' ORDER BY `id` DESC";
	@$res_order = mysql_query($query_order) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n_order = mysql_num_rows($res_order);

	for ($i_order=0; $i_order<$n_order; $i_order++) {
		$row_order = mysql_fetch_array($res_order);
		$order_id = $row_order['id'];
		$user_id = $row_order['user_id'];
		$order_price = $row_order['price'];

		$query_user = "SELECT `family`, `name`, `otch`, `phone`, `email` FROM $user_db_name WHERE `id`='$user_id'";
		@$res_user = mysql_query($query_user) or die("<li>Err: $query_user<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_user = mysql_fetch_array($res_user);
		$order_user = $row_user['family'].' '.$row_user['name'].' '.$row_user['otch'];

		// Вставляем текст в ячейку A$next_cell
		$sheet->setCellValue("A$next_cell", $order_user);
		$sheet->getStyle("A$next_cell")->getFill()->setFillType(
		    PHPExcel_Style_Fill::FILL_SOLID);
		$sheet->getStyle("A$next_cell")->getFill()->getStartColor()->setRGB('EEEEEE');
		// Объединяем ячейки
		$sheet->mergeCells("A$next_cell:F$next_cell");
		// Выравнивание текста
		$sheet->getStyle("A$next_cell")->getAlignment()->setHorizontal(
		    PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$next_cell++;

		$sheet->setCellValue("A$next_cell", 'Фото');
		// Выравнивание текста
		$sheet->getStyle("A$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$sheet->setCellValue("C$next_cell", 'Размер');
		// Выравнивание текста
		$sheet->getStyle("C$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$sheet->setCellValue("D$next_cell", 'Стоимость');
		// Выравнивание текста
		$sheet->getStyle("D$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->setCellValue("E$next_cell", 'Кол-во');
		// Выравнивание текста
		$sheet->getStyle("E$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->setCellValue("F$next_cell", 'Цена');
		// Выравнивание текста
		$sheet->getStyle("F$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$next_cell++;



		$query_photo = "SELECT `photo_id` FROM $order_select_db_name WHERE `order_id`='$order_id' GROUP BY `photo_id`";
		@$res_photo = mysql_query($query_photo) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$n_photo = mysql_num_rows($res_photo);

		for ($sel=0; $sel<$n_photo; $sel++) {
			$row_photo = mysql_fetch_array($res_photo);
			$photo_id = $row_photo['photo_id'];

			$query_sel = "SELECT `file_name` FROM $photo_db_name WHERE `id`='$photo_id'";
			@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row_sel = mysql_fetch_array($res_sel);
			$photo_file_name = $row_sel['file_name'];

			// Вставляем текст в ячейку A_$next_cell
			$sheet->setCellValue("A$next_cell", $photo_file_name);
			// Выравнивание текста
			$sheet->getStyle("A$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$query_order1 = "SELECT `id`, `size_id`, `count` FROM $order_select_db_name WHERE `order_id`='$order_id' AND `photo_id`='$photo_id'";
			@$res_order1 = mysql_query($query_order1) or die("<li>Err: $query_order<br>".mysql_errno()." : ".mysql_error()."<br>");
			$size_n1 = mysql_num_rows($res_order1);
			for ($size_i=0; $size_i<$size_n1; $size_i++) {
				$row_order1 = mysql_fetch_array($res_order1);
				$select_id = $row_order1['id'];
				$size_id = $row_order1['size_id'];
				$count = $row_order1['count'];

				$query_sel1 = "SELECT `title`, `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
				@$res_sel1 = mysql_query($query_sel1) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
				$row_sel1 = mysql_fetch_array($res_sel1);
				$size_title = $row_sel1['title'];
				$size_price = $row_sel1['price'];

				$sheet->setCellValue("C$next_cell", $size_title);
				// Выравнивание текста
				$sheet->getStyle("C$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$sheet->setCellValue("D$next_cell", $size_price);
				// Выравнивание текста
				$sheet->getStyle("D$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$sheet->setCellValue("E$next_cell", $count);
				// Выравнивание текста
				$sheet->getStyle("E$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$size_cena = $size_price * $count;
				$sheet->setCellValue("F$next_cell", $size_cena);
				// Выравнивание текста
				$sheet->getStyle("F$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$next_cell++;

			}

		}

		$sheet->setCellValue("E$next_cell", "Итого:");
		// Выравнивание текста
		$sheet->getStyle("E$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->setCellValue("F$next_cell", $order_price);
		// Выравнивание текста
		$sheet->getStyle("F$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$gal_price = $gal_price + $order_price;
		$next_cell++;
		$next_cell++;

	}


	$sheet->setCellValue("E$next_cell", "Итого по $title:");
	// Выравнивание текста
	$sheet->getStyle("E$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$sheet->setCellValue("F$next_cell", $gal_price);
	// Выравнивание текста
	$sheet->getStyle("F$next_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);





	// Выводим HTTP-заголовки
	 header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
	 header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	 header ( "Cache-Control: no-cache, must-revalidate" );
	 header ( "Pragma: no-cache" );
	 header ( "Content-type: application/vnd.ms-excel" );
	 header ( "Content-Disposition: attachment; filename=matrix" );
	 
	// Выводим содержимое файла
	 $objWriter = new PHPExcel_Writer_Excel5($xls);
	 $objWriter->save('php://output');

	 die;
}








?>

