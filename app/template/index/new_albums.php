<?php
/**
 * @var \App\System\Controller $this
 */
?>
<table width=100% style="border:1px solid #AAA;">
<tr>
	<td bgcolor=#e1eaff style="border:1px solid #AAA; height: 20px; padding: 5px;">
		<h2><a href="/newalbums?getNewAlbums">Новые альбомы</a></h2>
	</td>
</tr>
<tr>
	<td style="padding-top: 10px; padding-bottom: 10px;">
		<table border=0 width=100%>
<tr>
<?php
$im_moderator = $myrow->isModerator() ? '': 'AND pa.pass = 0';
$my_realstatus = $myrow->isReal() ? '': 'AND pa.albvisibility <> 3';

$sql = "select u.login, u.gender, pa.aid, pa.count, pa.pass,
  (select 
     CONCAT(pid,':',filepath,'thumb_',filename)
   from 
     photo_pictures pp 
   where 
     pp.aid = pa.aid and 
     filename != '' AND 
     filepath != '' ORDER by pid LIMIT 1) album_thumbnail
FROM photo_albums pa 
JOIN users u on u.id = pa.us_id_album and u.status <> 2 
WHERE pa.count !=0 $im_moderator $my_realstatus 
ORDER BY pa.aid DESC LIMIT 0,7";

$sth = $dbh->query($sql);
while ($row = $sth->fetch()) { $tmp = explode(':', $row['album_thumbnail']); ?>
<td valign=top align=center>
<a href="/albums_<?=$row['aid'];?>_<?= $tmp[0]?>">
	<div class="border-box" style="background: url(<?=$tmp[1]?>) no-repeat center;width: 70px; height: 70px; margin:2px; padding:2px;"></div>
</a>
<?php 
	if($row['pass']) echo "<span style='color:#F00'>Пароль</span><br />";
	echo $row['count'].' фото'; 
?>
</td>
<?php }?>
</tr>
</table>
</td>
</tr>
</table>