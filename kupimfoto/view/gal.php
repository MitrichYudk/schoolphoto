<?php

if ($action == 'show') {

	$tpl_data['title'] = 'Галереи';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184">Галереи</li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=gal&action=add">Добавить галерею</a></li>
	</ul>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = '<p>стр: ';

	//выводим страницы
	$query = "SELECT `id` FROM $gal_db_name";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	$page_count = intval($n / $gal_per_page);
	for ($i=1; $i<=$page_count+1;$i++){
		if ($page != $i) {
			$tpl_data['data'] .= " <a href=?obj=gal&action=show&page=".$i.">".$i."</a> ";
		}
		else
		{
			$tpl_data['data'] .= "<b>[".$i."]</b>";
		}
	}


	$tpl_data['data'] .= "
	<form action='admin.php?obj=gal&action=search'; method='post'><table align=center><tr align=left valign=bottom><td><input type=text name='q' size=22></td><td><div id='wrapper'><input type=submit NAME=submit value='Искать'></div></td></tr></table></form>
	<table align=center><tr align=center><td>Номер</td><td>Статус</td><td>Заголовок</td><td>Ссылка для покупателя</td><td>Пароль</td><td>Кол-во в<br>фотокниге</td><td>Стата</td><td>Редактирование</td></tr>";

	$limit_n = ($page - 1) * $gal_per_page;
	$query = "SELECT `id`, `pass`, `title`, `show_desc`, `hide_desc`, `book_count`, `stat`, `status` FROM $gal_db_name ORDER BY `id` DESC LIMIT $limit_n, $gal_per_page";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$gal_id = $row['id'];
		$gal_pass = $row['pass'];
		$gal_title = $row['title'];
		$show_desc = $row['show_desc'];
		$gal_hide_desc = $row['hide_desc'];
		$book_count = $row['book_count'];
		$gal_stat = $row['stat'];
		$gal_status = $row['status'];

		if ($gal_status == 'on') {
			$status_line = "<a href=?obj=gal&action=off&gal_id=$gal_id><img src='data/order_on.png' border='0'></a>";
		}
		if ($gal_status == 'off') {
			$status_line = "<a href=?obj=gal&action=on&gal_id=$gal_id><img src='data/order_off.png' border='0'></a>";
		}

		$gal_link = "<a href=index.php?id=$gal_id target=_new>Ссылка</a>";

		$tpl_data['data'] .= "<tr align=center><td>$gal_id</td><td>$status_line</td><td>$gal_title</td><td>$gal_link</td><td>$gal_pass</td><td>$book_count</td><td>$gal_stat</td>
		<td>
		<a href='?obj=gal&action=edit&gal_id=$gal_id' title='Редактировать'><img src='data/edit.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=photo&gal_id=$gal_id' title='Фотографии'><img src='data/picture.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=size&gal_id=$gal_id' title='Размеры и цены'><img src='data/coins.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=del&gal_id=$gal_id' title='Удалить'><img src='data/del.png' border='0'></a></td>
		</tr><tr align=left><td>&nbsp;</td><td colspan=7>$show_desc (<i>$gal_hide_desc</i>)</td></tr>
		";


	}

	$tpl_data['data'] .= '</table>';


}

if ($action == 'on') {

	$query = "UPDATE $gal_db_name SET `status`='on' WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "UPDATE $order_db_name SET `gal_status`='on' WHERE `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=show");

}

if ($action == 'off') {

	$query = "UPDATE $gal_db_name SET `status`='off' WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "UPDATE $order_db_name SET `gal_status`='off' WHERE `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=show");

}


if ($action == 'add') {

	$tpl_data['title'] = 'Добавляем галерею';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<ul>
	<li class="item-184">Добавить галерею</li>
	</ul>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=gal&action=add_1'; method='post'>
	<table align=center>
	<tr valign=top>
		<td align=right>&nbsp;Пароль:</td>
		<td colspan=20><input type=text autocomplete='off' name='gal_pass' size=12></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Заголовок:</td>
		<td colspan=20><input type=text autocomplete='off' name='gal_title' size=42></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Кол-во фотографий<br>в фотокниге:</td>
		<td colspan=20><input type=text autocomplete='off' name='book_count' size=12></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Отключить ПКМ:</td>
		<td colspan=20><label><input type=checkbox name='is_save' checked> Да</label></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Текст для<br>покупателя:</td>
		<td colspan=20><textarea cols=42 rows=12 name='gal_show_desc'></textarea></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Текст для себя<br> (невидимый):</td>
		<td colspan=20><textarea cols=42 rows=12 name='gal_hide_desc'></textarea></td>
	</tr>
	<tr align=center>
		<td colspan=2><input type=submit NAME=submit value='Добавить'></td>
	</tr>
	</table>
	</form>
	";

}

if ($action == 'add_1') {

	$gal_pass = $_POST['gal_pass'];
	$gal_title = $_POST['gal_title'];
	$book_count = $_POST['book_count'];
	$gal_show_desc = $_POST['gal_show_desc'];
	$gal_hide_desc = $_POST['gal_hide_desc'];
	$search_index = $gal_title.' '.$gal_show_desc.' '.$gal_hide_desc;

	$is_save = $_POST['is_save'];
	if ($is_save == 'on') {
		$is_save = 'on';
	} else {
		$is_save = 'off';
	}


	$end_date = time() + 86400 * 30;

	$query = "INSERT INTO $gal_db_name VALUES('', '$gal_pass', '$gal_title', '$gal_show_desc', '$gal_hide_desc', '$search_index', '$book_count', '0', 'on', '$is_save', '$end_date')";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$gal_id = mysql_insert_id();


	//добавляем стандартные размеры
	$query = "SELECT `title`, `price` FROM $size_db_name ORDER BY `id` DESC";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$size_title = $row['title'];
		$size_price = $row['price'];

		$query_ins = "INSERT INTO $gal_size_db_name VALUES('', '$gal_id', '$size_title', '$size_price')";
		@$res_ins = mysql_query($query_ins) or die("<li>Err: $query_ins<br>".mysql_errno()." : ".mysql_error()."<br>");

	}


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=show");

}


if ($action == 'edit') {

	$query = "SELECT `pass`, `title`, `show_desc`, `hide_desc`, `book_count`, `is_save` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$gal_pass = $row['pass'];
	$gal_title = $row['title'];
	$gal_show_desc = $row['show_desc'];
	$gal_hide_desc = $row['hide_desc'];
	$book_count = $row['book_count'];
	$is_save = $row['is_save'];
	if ($is_save == 'on') {
		$is_save_line = 'checked';
	} else {
		$is_save_line = '';
	}


	$tpl_data['title'] = 'Редактируем галерею';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=gal&action=add">Добавить галерею</a></li>
	</ul>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=gal&action=edit_1&gal_id=$gal_id'; method='post'>
	<table align=center>
	<tr valign=top>
		<td align=right>&nbsp;Пароль:</td>
		<td colspan=20><input type=text autocomplete='off' name='gal_pass' size=12 value='$gal_pass'></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Заголовок:</td>
		<td colspan=20><input type=text autocomplete='off' name='gal_title' size=42 value='$gal_title'></td>
	</tr>	
	<tr valign=top>
		<td align=right>&nbsp;Кол-во фотографий<br>в фотокниге:</td>
		<td colspan=20><input type=text autocomplete='off' name='book_count' size=12 value='$book_count'></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Отключить ПКМ:</td>
		<td colspan=20><label><input type=checkbox name='is_save' $is_save_line> Да</label></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Текст для<br>покупателя:</td>
		<td colspan=20><textarea cols=42 rows=12 name='gal_show_desc'>$gal_show_desc</textarea></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Текст для себя<br> (невидимый):</td>
		<td colspan=20><textarea cols=42 rows=12 name='gal_hide_desc'>$gal_hide_desc</textarea></td>
	</tr>
	<tr align=center>
		<td colspan=2><input type=submit NAME=submit value='Редактировать'></td>
	</tr>
	</table>
	</form>
	";

}


if ($action == 'edit_1') {

	$gal_pass = $_POST['gal_pass'];
	$gal_title = $_POST['gal_title'];
	$gal_show_desc = $_POST['gal_show_desc'];
	$gal_hide_desc = $_POST['gal_hide_desc'];
	$book_count = $_POST['book_count'];
	$search_index = $gal_title.' '.$gal_show_desc.' '.$gal_hide_desc;

	$is_save = $_POST['is_save'];
	if ($is_save == 'on') {
		$is_save = 'on';
	} else {
		$is_save = 'off';
	}
	
	$query = "UPDATE $gal_db_name SET `pass`='$gal_pass', `title`='$gal_title', `show_desc`='$gal_show_desc', `hide_desc`='$gal_hide_desc', `book_count`='$book_count', `search_index`='$search_index', `is_save`='$is_save' WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=show");

}

if ($action == 'del') {

	$query = "DELETE FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "DELETE FROM $gal_size_db_name WHERE `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=show");

}


if ($action == 'search') {

	$q = $_POST['q'];

	$tpl_data['title'] = "Поиск `$q `";

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=gal&action=add">Добавить галерею</a></li>
	</ul>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = '<p>стр: ';

	//выводим страницы
	$query = "SELECT `id` FROM $gal_db_name WHERE `search_index` LIKE '%$q%'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	$page_count = intval($n / $gal_per_page);
	for ($i=1; $i<=$page_count+1;$i++){
		if ($page != $i) {
			$tpl_data['data'] .= " <a href=?obj=gal&action=show&page=".$i.">".$i."</a> ";
		}
		else
		{
			$tpl_data['data'] .= "<b>[".$i."]</b>";
		}
	}


	$tpl_data['data'] .= "
	<form action='admin.php?obj=gal&action=search'; method='post'><table align=center><tr align=left valign=bottom><td><input type=text name='q' size=22></td><td><div id='wrapper'><input type=submit NAME=submit value='Искать'></div></td></tr></table></form>
	<table align=center><tr align=center><td>Номер</td><td>Статус</td><td>Заголовок</td><td>Ссылка для покупателя</td><td>Пароль</td><td>Кол-во в<br>фотокниге</td><td>Стата</td><td>Редактирование</td></tr>";

	$limit_n = ($page - 1) * $gal_per_page;
	$query = "SELECT `id`, `pass`, `title`, `show_desc`, `hide_desc`, `book_count`, `stat`, `status` FROM $gal_db_name WHERE `search_index` LIKE '%$q%' ORDER BY `id` DESC LIMIT $limit_n, $gal_per_page";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$gal_id = $row['id'];
		$gal_pass = $row['pass'];
		$gal_title = $row['title'];
		$show_desc = $row['show_desc'];
		$gal_hide_desc = $row['hide_desc'];
		$book_count = $row['book_count'];
		$gal_stat = $row['stat'];
		$gal_status = $row['status'];

		if ($gal_status == 'on') {
			$status_line = "<a href=?obj=gal&action=off&gal_id=$gal_id><img src='data/order_on.png' border='0'></a>";
		}
		if ($gal_status == 'off') {
			$status_line = "<a href=?obj=gal&action=on&gal_id=$gal_id><img src='data/order_off.png' border='0'></a>";
		}

		$gal_link = "<a href=index.php?id=$gal_id target=_new>Ссылка</a>";

		$tpl_data['data'] .= "<tr align=center><td>$gal_id</td><td>$status_line</td><td>$gal_title</td><td>$gal_link</td><td>$gal_pass</td><td>$book_count</td><td>$gal_stat</td>
		<td>
		<a href='?obj=gal&action=edit&gal_id=$gal_id' title='Редактировать'><img src='data/edit.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=photo&gal_id=$gal_id' title='Фотографии'><img src='data/picture.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=size&gal_id=$gal_id' title='Размеры и цены'><img src='data/coins.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=del&gal_id=$gal_id' title='Удалить'><img src='data/del.png' border='0'></a></td>
		</tr><tr align=left><td>&nbsp;</td><td colspan=7>$show_desc (<i>$gal_hide_desc</i>)</td></tr>
		";


	}

	$tpl_data['data'] .= '</table>';


}





















if ($action == 'size') {

	$tpl_data['title'] = 'Размеры и цены галереи';

	$tpl_data['menu'] = "
	<li class='item-184'><a href='admin.php?obj=order&action=show'>Заказы</a></li>
	<li class='item-184'><a href='admin.php?obj=gal&action=show'>Галереи</a></li>
	<ul>
	<li class='item-184'><a href='admin.php?obj=gal&action=add_size&gal_id=$gal_id'>Добавить размер галерее</a></li>
	</ul>
	<li class='item-184'>Стандартные размеры</li>

	";

	$tpl_data['data'] = '<center><table align=center><tr align=center><td>Размер</td><td>Цена</td><td>Редактирование</td></tr>';

	$query = "SELECT `id`, `title`, `price` FROM $gal_size_db_name WHERE `gal_id`='$gal_id' ORDER BY `id` DESC";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$size_id = $row['id'];
		$size_title = $row['title'];
		$size_price = $row['price'];
		$tpl_data['data'] .= "<tr align=center><td>$size_title</td><td>$size_price</td><td>
		<a href='?obj=gal&action=edit_size&gal_id=$gal_id&size_id=$size_id' title='Редактировать'><img src='data/edit.png' border='0'></a> &nbsp;
		<a href='?obj=gal&action=del_size&gal_id=$gal_id&size_id=$size_id' title='Удалить'><img src='data/del.png' border='0'></a></td>";


	}

	$tpl_data['data'] .= '</table>';

}

if ($action == 'add_size') {

	$tpl_data['title'] = 'Добавляем размер галерее';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<ul>
	<li class="item-184">Добавить размер галерее</li>
	</ul>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=gal&action=add_size_1&gal_id=$gal_id'; method='post'>
	<table align=center>
	<tr valign=top>
		<td align=right>&nbsp;Размер:</td>
		<td colspan=20><input type=text autocomplete='off' name='size_title' size=12></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Стоимость:</td>
		<td colspan=20><input type=text autocomplete='off' name='size_price' size=12></td>
	</tr>
	<tr align=center>
		<td colspan=2><input type=submit NAME=submit value='Добавить'></td>
	</tr>
	</table>
	</form>
	";

}


if ($action == 'add_size_1') {

	$size_title = $_POST['size_title'];
	$size_price = $_POST['size_price'];

	$query = "INSERT INTO $gal_size_db_name VALUES('', '$gal_id', '$size_title', '$size_price')";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=size&gal_id=$gal_id");

}

if ($action == 'edit_size') {

	$query = "SELECT `title`, `price` FROM $gal_size_db_name WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$size_title = $row['title'];
	$size_price = $row['price'];

	$tpl_data['title'] = 'Редактируем размер галереи';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=gal&action=edit_size_1&gal_id=$gal_id&size_id=$size_id'; method='post'>
	<table align=center>
	<tr valign=top>
		<td align=right>&nbsp;Размер:</td>
		<td colspan=20><input type=text autocomplete='off' name='size_title' size=12 value='$size_title'></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Стоимость:</td>
		<td colspan=20><input type=text autocomplete='off' name='size_price' size=12 value='$size_price'></td>
	</tr>
	<tr align=center>
		<td colspan=2><input type=submit NAME=submit value='Редактировать'></td>
	</tr>
	</table>
	</form>
	";

}


if ($action == 'edit_size_1') {

	$size_title = $_POST['size_title'];
	$size_price = $_POST['size_price'];

	$query = "UPDATE $gal_size_db_name SET `title`='$size_title', `price`='$size_price' WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=size&gal_id=$gal_id");

}


if ($action == 'del_size') {

	$query = "DELETE FROM $gal_size_db_name WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=size&gal_id=$gal_id");

}















if ($action == 'photo') {

	$tpl_data['title'] = 'Фотографии галереи';

	$tpl_data['menu'] = "
	<li class='item-184'><a href='admin.php?obj=order&action=show'>Заказы</a></li>
	<li class='item-184'><a href='admin.php?obj=gal&action=show'>Галереи</a></li>
	<li class='item-184'><a href='admin.php?obj=size&action=show'>Стандартные размеры</a></li>

	";

	$tpl_data['data'] = '<p>';
	
	//форма загрузки фото
	$tpl_data['data'] .= "<form enctype=multipart/form-data action=admin.php?obj=gal&action=upload&gal_id=$gal_id method=post>
		<input name='userFile[]' type='file' multiple>
		<br><input type='submit' name='save' value='Загрузить' />
		</form>";


	//форма загрузки фото по FTP
	$tpl_data['data'] .= "<p style='clear:both'><p><a href='admin.php?obj=gal&action=ftp&gal_id=$gal_id'>По FTP</a>";



	//форма множественного удаления / перемещения
	$tpl_data['data'] .= "<form action=admin.php?obj=gal&action=multy&gal_id=$gal_id method=post>";



	//выводим страницы
	$tpl_data['data'] .= '<p>&nbsp;стр.: ';
	$query = "SELECT `id` FROM $photo_db_name WHERE `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	$page_count = intval($n / $photo_per_page);
	for ($i=1; $i<=$page_count+1;$i++){
		if ($page != $i) {
			$tpl_data['data'] .= " <a href=?obj=gal&action=photo&gal_id=$gal_id&page=".$i.">".$i."</a> ";
		}
		else
		{
			$tpl_data['data'] .= "<b>[".$i."]</b>";
		}
	}
	$limit_n = ($page - 1) * $photo_per_page;

	$query = "SELECT `id`, `file_name`, `src_min`, `src_big` FROM $photo_db_name WHERE `gal_id`='$gal_id' LIMIT $limit_n, $photo_per_page";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);
	$rows_per_page = $n / $cols_per_page;

	$tpl_data['data'] .= '<center><table align=center>';

	for ($i=0; $i<$rows_per_page; $i++) {
		$tpl_data['data'] .= "<tr align=center>";
		for ($j=0; $j<$cols_per_page; $j++) {
			$row = mysql_fetch_array($res);
			$photo_id = $row['id'];
			$file_name = $row['file_name'];
			$photo_src_min = $row['src_min'];
			$photo_src_big = $row['src_big'];
			
			if ($photo_id) {
				$tpl_data['data'] .= "<td width='210' height='210' bgcolor='white'><a id='example4' name='a$photo_id' href='$photo_src_big'><img src='$photo_src_min'></a><br>$file_name&nbsp;&nbsp;&nbsp;
				<a href='?obj=gal&action=del_photo&gal_id=$gal_id&photo_id=$photo_id&page=$page' title='Удалить'><img src='data/del.png' border='0'></a>
				<label><input type='checkbox' name='multy_id_$photo_id'>Выбрать</label>
				</td>";
			} else {
				$tpl_data['data'] .= "<td width='210' height='210' bgcolor='white'></td>";
			}

		}
		$tpl_data['data'] .= "</tr>";
	}
	$tpl_data['data'] .= "</table>";
	$tpl_data['data'] .= "<br><nobr><input type='submit' name='save' value='C выбранными' />
	<select name='sub_action' id='sub_action' onchange = 'IsMoveSelect ()'; align=left>
		<option value='del' selected>Удалить</option>
		<option value='copy'>Скопировать</option>
		<option value='move'>Переместить</option>
	</select>";
	
	//выбор галереи, куда перемещать
	$tpl_data['data'] .= "<select name='where_move' id='where_move' style='display:none;' align=left>";

	$query = "SELECT `id`, `title` FROM $gal_db_name";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);
	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$move_gal_id = $row['id'];
		$move_gal_title = $row['title'];
		$tpl_data['data'] .= "<option value='$move_gal_id'>$move_gal_title</option>";
	}
	$tpl_data['data'] .= "</select></nobr>";

	$tpl_data['data'] .= "</form>";


}



if ($action == 'upload') {

	$today_image_dir = create_today_image_dir();
	$file_n = count($_FILES['userFile']['name']);

	for ($i=0; $i<$file_n; $i++) {

		$file_name = $_FILES['userFile']['name'][$i];
		list($file_name_left, $file_name_ext) = explode('.', $file_name);

		@unlink($today_image_dir.$file_name);
		@unlink($today_image_dir.$file_name_left.'-min.'.$file_name_ext);

		if (move_uploaded_file($_FILES['userFile']['tmp_name'][$i], $today_image_dir.$file_name))
		{

			//уменьшаем фотографию
			$canvas_big = imagecreatefromjpeg($today_image_dir.$file_name);
			$canvas_big_width = imagesx($canvas_big);
			$canvas_big_height = imagesy($canvas_big);

			if ($canvas_big_width > $canvas_big_height) {
				//уменьшаем ширину
				$d_width = $min_size / $canvas_big_width;
				$min_height = $canvas_big_height * $d_width;
				$canvas_min = imagecreatetruecolor($min_size, $min_height);
				imagecopyresized($canvas_min, $canvas_big, 0, 0, 0, 0, $min_size, $min_height, $canvas_big_width, $canvas_big_height);

			} else {
				//уменьшаем высоту
				$d_height = $min_size / $canvas_big_height;
				$min_width = $canvas_big_width * $d_height;
				$canvas_min = imagecreatetruecolor($min_width, $min_size);
				imagecopyresized($canvas_min, $canvas_big, 0, 0, 0, 0, $min_width, $min_size, $canvas_big_width, $canvas_big_height);

			}
			imagejpeg($canvas_min, $today_image_dir.$file_name_left.'-min.'.$file_name_ext);
			imagejpeg($canvas_big, $today_image_dir.$file_name);
			

			$today_image_src = create_today_image_src();

			$min_file_name = $today_image_src.$file_name_left.'-min.'.$file_name_ext;
			$big_file_name = $today_image_src.$file_name;

			$query = "INSERT INTO $photo_db_name VALUES('', '$gal_id', '$file_name', '$min_file_name', '$big_file_name')";
			@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");	
		}

	}


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=photo&gal_id=$gal_id");

}




if ($action == 'ftp') {

	$today_image_dir = create_today_image_dir();

	$files_all = scandir('temp');
	$file_n = count($files_all);
	$tpl_data['data'] .= "files = $file_n";

	for ($i=2; $i<$file_n; $i++) {

		$file_name = $files_all[$i];
		list($file_name_left, $file_name_ext) = explode('.', $file_name);

		@unlink($today_image_dir.$file_name);
		@unlink($today_image_dir.$file_name_left.'-min.'.$file_name_ext);

		$tpl_data['data'] .= "<li>$file_name";


		if (copy("temp/$file_name", $today_image_dir.$file_name))
		{

			//уменьшаем фотографию
			$canvas_big = imagecreatefromjpeg($today_image_dir.$file_name);
			$canvas_big_width = imagesx($canvas_big);
			$canvas_big_height = imagesy($canvas_big);

			if ($canvas_big_width > $canvas_big_height) {
				//уменьшаем ширину
				$d_width = $min_size / $canvas_big_width;
				$min_height = $canvas_big_height * $d_width;
				$canvas_min = imagecreatetruecolor($min_size, $min_height);
				imagecopyresized($canvas_min, $canvas_big, 0, 0, 0, 0, $min_size, $min_height, $canvas_big_width, $canvas_big_height);

			} else {
				//уменьшаем высоту
				$d_height = $min_size / $canvas_big_height;
				$min_width = $canvas_big_width * $d_height;
				$canvas_min = imagecreatetruecolor($min_width, $min_size);
				imagecopyresized($canvas_min, $canvas_big, 0, 0, 0, 0, $min_width, $min_size, $canvas_big_width, $canvas_big_height);

			}
			imagejpeg($canvas_min, $today_image_dir.$file_name_left.'-min.'.$file_name_ext);
			imagejpeg($canvas_big, $today_image_dir.$file_name);
			

			$today_image_src = create_today_image_src();

			$min_file_name = $today_image_src.$file_name_left.'-min.'.$file_name_ext;
			$big_file_name = $today_image_src.$file_name;

			$query = "INSERT INTO $photo_db_name VALUES('', '$gal_id', '$file_name', '$min_file_name', '$big_file_name')";
			@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");	

		}



	}

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=photo&gal_id=$gal_id");

}













if ($action == 'del_photo') {

	$query = "DELETE FROM $photo_db_name WHERE `id`='$photo_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	$query = "SELECT `id` FROM $photo_db_name WHERE `gal_id`='$gal_id' AND `id`>'$photo_id' ORDER BY `id` ASC";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$new_photo_id = $row['id'];

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=photo&gal_id=$gal_id&page=$page#a$new_photo_id");

}



if ($action == 'multy') {

	$where_move_id = $_POST['where_move'];
	$query = "SELECT `id`, `file_name`, `src_min`, `src_big` FROM $photo_db_name WHERE `gal_id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$photo_id = $row['id'];
		$file_name = $row['file_name'];
		$src_min = $row['src_min'];
		$src_big = $row['src_big'];

		$is_select = $_POST["multy_id_$photo_id"];
		if ($is_select) {
			
			if ($sub_action == 'del') {
				$query_del = "DELETE FROM $photo_db_name WHERE `id`='$photo_id'";
				@$res_del = mysql_query($query_del) or die("<li>Err: $query_del<br>".mysql_errno()." : ".mysql_error()."<br>");
			}

			if ($sub_action == 'move') {
				$query_del = "UPDATE $photo_db_name SET `gal_id`='$where_move_id' WHERE `id`='$photo_id'";
				@$res_del = mysql_query($query_del) or die("<li>Err: $query_del<br>".mysql_errno()." : ".mysql_error()."<br>");
			}

			if ($sub_action == 'copy') {
				$query_del = "INSERT INTO $photo_db_name VALUES('', '$where_move_id', '$file_name', '$src_min', '$src_big')";
				@$res_del = mysql_query($query_del) or die("<li>Err: $query_del<br>".mysql_errno()." : ".mysql_error()."<br>");	
			}



		}
	}


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=gal&action=photo&gal_id=$gal_id&page=$page");

}

















?>

