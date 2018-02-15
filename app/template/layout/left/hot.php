<?php
// todo:: Плохой запрос!!! Переделать!!!
$sql = 'select hot_text, login, regdate, photo_visibility, id, pic1, gender, city, birthday 
  from users 
  where hot_time > NOW() 
  and pic1 <> \'\' and pic1 <> \'net-avatara.jpg\' 
  and `status` <> 2 
order by rand() limit 1';

$user_hot = $this->dbh->query($sql)->fetch();

if (!empty($user_hot))  :?>
    <table width="235" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0;">
        <tr>
            <td align="center" height="33" style="background: url('img/tablehead.jpg') no-repeat;">
                <b>ГОРЯЧИЕ ЗНАКОМСТВА</b>
            </td>
        </tr>
        <tr>
            <td align="center">

                <table border="0">
                    <tr>
                        <td height="100" align="center" valign="center">

                            <a href="<?php echo $this->myrow->isUser() ? '/id' . $user_hot['id'] : '#showimagemsg' ?>">
                                <div class="avatar" style="background-image: url(<?php echo avatar($this->myrow, $user_hot['pic1'],
                                    $user_hot['photo_visibility']); ?>)"></div>
                            </a>
                            <b>дана79</b>
                            <br>
                            <table border="0" width="200" bgcolor="#ffffff">
                                <tbody>
                                <tr>
                                    <td align="center" valign="center" style="overflow: hidden">
                                        <?php echo mb_substr($user_hot['hot_text'], 0, 75); ?> ...
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                        </td>
                    </tr>
                </table>

                <a href="/hotmeet"><b>Все объявления</b></a>
                <br>
            </td>
        </tr>
    </table>
<?php endif;