<?php

if ($action == 'show') {

	$tpl_data['title'] = 'Стандартные размеры';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184">Стандартные размеры</li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=size&action=add">Добавить размер</a></li>
	</ul>

	';

	$tpl_data['data'] = '<center><table align=center><tr align=center><td>Размер</td><td>Цена</td><td>Редактирование</td></tr>';

	$query = "SELECT `id`, `title`, `price` FROM $size_db_name ORDER BY `id` DESC";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$n = mysql_num_rows($res);

	for ($i=0; $i<$n; $i++) {
		$row = mysql_fetch_array($res);
		$size_id = $row['id'];
		$size_title = $row['title'];
		$size_price = $row['price'];
		$tpl_data['data'] .= "<tr align=center><td>$size_title</td><td>$size_price</td><td>
		<a href='?obj=size&action=edit&size_id=$size_id' title='Редактировать'><img src='data/edit.png' border='0'></a> &nbsp;
		<a href='?obj=size&action=del&size_id=$size_id' title='Удалить'><img src='data/del.png' border='0'></a></td>";


	}

	$tpl_data['data'] .= '</table>';


}

if ($action == 'add') {

	$tpl_data['title'] = 'Добавляем размер';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	<ul>
	<li class="item-184">Добавить размер</li>
	</ul>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=size&action=add_1'; method='post'>
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

if ($action == 'add_1') {

	$size_title = $_POST['size_title'];
	$size_price = $_POST['size_price'];

	$query = "INSERT INTO $size_db_name VALUES('', '$size_title', '$size_price')";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=size&action=show");

}



if ($action == 'edit') {

	$query = "SELECT `title`, `price` FROM $size_db_name WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$size_title = $row['title'];
	$size_price = $row['price'];


	$tpl_data['title'] = 'Редактируем размер';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=order&action=show">Заказы</a></li>
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=size&action=show">Стандартные размеры</a></li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=size&action=add">Добавить размер</a></li>
	</ul>
	';

	$tpl_data['data'] = "
	<center><form action='admin.php?obj=size&action=edit_1&size_id=$size_id'; method='post'>
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

if ($action == 'edit_1') {

	$size_title = $_POST['size_title'];
	$size_price = $_POST['size_price'];

	$query = "UPDATE $size_db_name SET `title`='$size_title', `price`='$size_price' WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=size&action=show");

}

if ($action == 'del') {

	$query = "DELETE FROM $size_db_name WHERE `id`='$size_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");


	Header("HTTP/1.1 301 Permanent Redirect");
	Header("Location: admin.php?obj=size&action=show");

}




?>

