<table width=100% style="border:1px solid #AAA;">
	<tr>
		<td bgcolor="#e1eaff" style="border:1px solid #AAA;height:20px;padding:5px;">
			<h2><a href="/newmeet_1">Новые анкеты свингеров</a></h2>
		</td>
	</tr>
<tr>
	<td style="padding: 10px 0">
<table border="0" width="100%">
<tr>
<?php
$sql = 'select login, regdate, photo_visibility, id, pic1,gender, city, birthday
  from users 
  where pic1 <> \'net-avatara.jpg\' and `status` = 1  
order by id desc limit 7';

$new_users = $dbh->query($sql)->fetchAll();

if(!empty($new_users)) {
    foreach ((array)$new_users as $user) :?>
        <td align="center" valign="top">
            <a href="<?php echo $myrow->isUser() ? '/id' . $user['id'] : '#showimagemsg'; ?>">
                <div class="border-box avatar"
                        style="background-image:url(<?php echo avatar($myrow, $user['pic1'],$user['photo_visibility']); ?>)">
                </div>
            </a>
            <img src="/img/newred.gif" width="34" height="15" alt="new">
            <br>
            <b><?php echo \App\Arrays\Genders::$gender[$user['gender']]; ?></b>
            <br>
            <span class="u-city-<?php echo (int)(mb_strtolower($myrow->city) === mb_strtolower($user['city'])); ?>"><?php echo html($user['city']); ?></span>
        </td>
    <?php endforeach;
}?>
</tr>
</table>
</td></tr>
</table>