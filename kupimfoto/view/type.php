<?php

if ($action == 'show') {

	$tpl_data['title'] = 'Стандартные размеры';


	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184">Стандартные размеры</li>
	<ul>
	<li class="item-184"><a href="admin.php?obj=type&action=add">Добавить размер</a></li>
	</ul>

	';

}


if ($action == 'add') {

	$tpl_data['title'] = 'Добавляем размер';

	$tpl_data['menu'] = '
	<li class="item-184"><a href="admin.php?obj=gal&action=show">Галереи</a></li>
	<li class="item-184"><a href="admin.php?obj=type&action=show">Стандартные размеры</a></li>
	<ul>
	<li class="item-184">Добавить размер</li>
	</ul>
	';

	$tpl_data['data'] = "
	<form action='index.php?obj=type&action=add_1'; method='post'>
	<table align=center>
	<tr valign=top>
		<td align=right>&nbsp;Размер:</td>
		<td colspan=20><input type=text autocomplete='off' name='title' size=42></td>
	</tr>
	<tr valign=top>
		<td align=right>&nbsp;Стоимость:</td>
		<td colspan=20><input type=text autocomplete='off' name='price' size=42></td>
	</tr>
	<tr align=center>
		<td colspan=2><input type=submit NAME=submit value='Добавить'></td>
	</tr>
	</table>
	</form>
	";




}





?>

