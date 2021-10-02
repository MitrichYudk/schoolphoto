<?php
include 'config.php';

$rnd = rand(0,100000);
$timestamp = time();

$page = $_GET['page'];
if (!isset($page)) {
	$page = 1;
}

$cookie_data['username'] = $_COOKIE['p5_username'];
$cookie_data['password'] = $_COOKIE['p5_password'];

$task = $_GET['task'];
if ($task == 'login') {
	$post_data['username'] = $_POST['p5_username'];
	$post_data['password'] = $_POST['p5_password'];
	SetCookie('p5_username', $post_data['username']);
	SetCookie('p5_password', $post_data['password']);
	$cookie_data['username'] = $post_data['username'];
	$cookie_data['password'] = $post_data['password'];
}

if ($task == 'unlogin') {
	SetCookie('p5_username', '');
	SetCookie('p5_password', '');
	$cookie_data['username'] = '';
	$cookie_data['password'] = '';
}


if (($cookie_data['username'] != $admin_data['username']) || ($cookie_data['password'] != $admin_data['password'])) {
	//пароль и логин не сошлись
	$tpl_data['title'] = 'Вход в админку';
	$tpl_data['login'] = '<a href="#login_form" id="login_pop">Войти</a>';
	$tpl_data['data'] = '<h3><p>&nbsp;</h3>';

} else {
	$tpl_data['login'] = '<a href="admin.php?task=unlogin" id="login_pop">Админ: Выйти</a>';

	$obj = $_GET['obj'];
	$action = $_GET['action'];
	$sub_action = $_POST['sub_action'];
	$gal_id = $_GET['gal_id'];
	$size_id = $_GET['size_id'];
	$photo_id = $_GET['photo_id'];
	$order_id = $_GET['order_id'];
	$select_id = $_GET['select_id'];

	if (!isset($obj)) {
		$obj = 'gal';
	}
	if (!isset($action)) {
		$action = 'show';
	}


	if ($obj == 'order') {
		include 'view/order.php';
	}
	if ($obj == 'gal') {
		include 'view/gal.php';
	}
	if ($obj == 'size') {
		include 'view/size.php';
	}



}




include 'tpl/admin.htm';
mysql_close();
?>

