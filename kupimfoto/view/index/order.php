<?php

$tpl_data['title'] = "Оформляем заказ";
$tpl_data['style_display'] = 'none';

$tpl_data['data'] = "
<center><form action='index.php?id=$gal_id&action=order_1'; method='post' class='rf' accept-charset='utf-8'>
<table align=center>
<tr valign=top>
	<td align=right>&nbsp;Фамилия:</td>
	<td colspan=20><input type=text class='rfield' name='family' size=32 value='$user_family'></td>
</tr>
<tr valign=top>
	<td align=right>&nbsp;Имя:</td>
	<td colspan=20><input type=text class='rfield' name='name' size=32 value='$user_name'></td>
</tr>
<tr valign=top>
	<td align=right>&nbsp;Отчество:</td>
	<td colspan=20><input type=text class='rfield' name='otch' size=32 value='$user_otch'></td>
</tr>
<tr valign=top>
	<td align=right>&nbsp;Телефон:</td>
	<td colspan=20><input type=text class='rfield' name='phone' size=32 value='$user_phone'></td>
</tr>
<tr valign=top>
	<td align=right>&nbsp;EMail:</td>
	<td colspan=20><input type=text class='rfield' name='email' size=32></td>
</tr>
<tr valign=top>
	<td align=right>&nbsp;Комментарий к заказу:</td>
	<td colspan=20><textarea rows=8 cols=23 name='comment'></textarea></td>
</tr>
<tr align=center>
	<td colspan=2><input class='btn_submit disabled' type=submit NAME=submit value='Заказать'></td>
</tr>
</table>
</form>
<p>Все поля формы обязательны для заполнения
";











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

