<?php
include 'config.php';

$rnd = rand(0,100000);
$timestamp = time();

$page = $_GET['page'];
if (!isset($page)) {
	$page = 1;
}

$is_admin = false;
if (($_COOKIE['p5_username'] == $admin_data['username']) && ($_COOKIE['p5_password'] == $admin_data['password'])) {
	$is_admin = true;
}

$gal_id = $_GET['id'];
$task = $_POST['task'];
$is_logged = false;

$tpl_data['menu_display'] = 'none';
if (!$gal_id) {
	//ввод номера галереи
	$tpl_data['style_display'] = 'none';

	if ($task == 'login') {
		$post_data['pass'] = $_POST['p5_pass'];
		$gal_id = intval($_POST['gal_id']);
		if ($gal_id) {

			$query = "SELECT `status` FROM $gal_db_name WHERE `id`='$gal_id'";
			@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
			$row = mysql_fetch_array($res);
			$gal_status = $row['status'];
			if ($gal_status == 'off') {
				$tpl_data['title'] = 'Извините, галерея недоступна';
				$tpl_data['data'] = "<center><h3>Извините, галерея недоступна</h3>";
			} else {
				$query = "SELECT `pass` FROM $gal_db_name WHERE `id`='$gal_id'";
				@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
				$row = mysql_fetch_array($res);
				$gal_pass = $row['pass'];
				if ($gal_pass == $post_data['pass']) {
					$is_logged = true;
					SetCookie('p5_pass', $post_data['pass']);
				} else {
					$tpl_data['title'] = 'Введите номер галереи и пароль';
					$tpl_data['data'] = "
					<center>
					<h3>$login_h1</h3>
					<form action='index.php'; method='post'>
						<table align=center>
						<tr valign=top>
							<td align=right>&nbsp;Номер галереи:</td>
							<td colspan=20><input type=text autocomplete='off' name='gal_id' size=8></td>
						</tr>
						<tr valign=top>
							<td align=right>&nbsp;Пароль:</td>
							<td colspan=20><input type=text autocomplete='off' name='p5_pass' size=8></td>
						</tr>
						<tr align=center>
							<td colspan=2 align=center><input type=submit NAME=submit value='Ввести'></td>
						</tr>
						</table>
						<input type=hidden name='task' value='login'>
					</form>
					";
				}
			}
		}
	} else {
		$tpl_data['menu_display'] = 'none';
		$tpl_data['title'] = 'Введите номер галереи и пароль';
		$tpl_data['data'] = "
		<center>
		<h3>$login_h1</h3>
		<form action='index.php'; method='post'>
			<table align=center>
			<tr valign=top>
				<td align=right>&nbsp;Номер галереи:</td>
				<td colspan=20><input type=text autocomplete='off' name='gal_id' size=8></td>
			</tr>
			<tr valign=top>
				<td align=right>&nbsp;Пароль:</td>
				<td colspan=20><input type=text autocomplete='off' name='p5_pass' size=8></td>
			</tr>
			<tr align=center>
				<td colspan=2 align=center><input type=submit NAME=submit value='Ввести'></td>
			</tr>
			</table>
			<input type=hidden name='task' value='login'>
		</form>
		";

	}
} else {
	//заход по ссылке с $gal_id
	$query = "SELECT `status` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$gal_status = $row['status'];
	if ($gal_status == 'off') {
		$tpl_data['title'] = 'Извините, галерея недоступна';
		$tpl_data['data'] = "<center><h3>Извините, галерея недоступна</h3>";
	} else {
		$cookie_data['pass'] = $_COOKIE['p5_pass'];
		//проверка пароля к уже введенной галерее
		if ($task == 'login') {
			$post_data['pass'] = $_POST['p5_pass'];
			SetCookie('p5_pass', $post_data['pass']);
			$cookie_data['pass'] = $post_data['pass'];
		}

		$query = "SELECT `pass` FROM $gal_db_name WHERE `id`='$gal_id'";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row = mysql_fetch_array($res);
		$gal_pass = $row['pass'];

		if (($cookie_data['pass'] != $gal_pass)) {
			//пароль и логин не сошлись
			$tpl_data['style_display'] = 'none';
			$tpl_data['menu_display'] = 'none';
			$tpl_data['title'] = 'Введите пароль';
			$tpl_data['data'] = "
			<center>
			<h3>Галерея №$gal_id</h3>
			$gal_show_desc
			<p><form action='index.php?id=$gal_id'; method='post'>
				<table align=center>
				<tr valign=top>
					<td align=right>&nbsp;Пароль:</td>
					<td colspan=20><input type=text autocomplete='off' name='p5_pass' size=8></td>
				</tr>
				<tr align=center>
					<td colspan=2 align=center><input type=submit NAME=submit value='Ввести'></td>
				</tr>
				</table>
				<input type=hidden name='task' value='login'>
			</form>
			";

		} else {
			$is_logged = true;
		}

}


}





if ($is_logged) {
	$tpl_data['menu_display'] = '';

	$user_id = $_COOKIE['p5_user'];
	if (!$user_id) {
		//добавляем нового
		$query = "INSERT INTO $user_db_name VALUES('', '', '', '', '', '')";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		$user_id = mysql_insert_id();
		SetCookie('p5_user', $user_id);

		//обновляем статистику
		if (!$is_admin) {
			$query = "UPDATE $gal_db_name SET `stat`=`stat`+1 WHERE `id`='$gal_id'";
			@$res = mysql_query($query) or die("<li>Error: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
		}
						
	}

	$query = "SELECT `title`, `show_desc` FROM $gal_db_name WHERE `id`='$gal_id'";
	@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");
	$row = mysql_fetch_array($res);
	$gal_title = $row['title'];
	$gal_show_desc = $row['show_desc'];


	$action = $_GET['action'];
	$sel_id = intval($_GET['sel_id']);
	$photo_id = intval($_GET['photo_id']);

	if (!$action) {
		include 'view/index/show.php';
	}
	if ($action == 'select') {
		include 'view/index/select.php';
	}
	if ($action == 'select_1') {
		include 'view/index/select_1.php';
	}
	if ($action == 'order') {
		include 'view/index/order.php';
	}
	if ($action == 'order_1') {
		include 'view/index/order_1.php';
	}


	if ($action == 'del_sel') {
		$query_sel = "SELECT `photo_id` FROM $select_db_name WHERE `id`='$sel_id'";
		@$res_sel = mysql_query($query_sel) or die("<li>Err: $query_sel<br>".mysql_errno()." : ".mysql_error()."<br>");
		$row_sel = mysql_fetch_array($res_sel);
		$photo_id = $row_sel['photo_id'];


		$query = "DELETE FROM $select_db_name WHERE `id`='$sel_id'";
		@$res = mysql_query($query) or die("<li>Err: $query<br>".mysql_errno()." : ".mysql_error()."<br>");

		Header("HTTP/1.1 301 Permanent Redirect");
		Header("Location: index.php?id=$gal_id&page=$page#a$photo_id");
	}
}








include 'tpl/index.htm';
mysql_close();
?>

