<?php
/**
 * @var array $comments
 */
use App\Components\Parse\All as AllParse;

$myrow = auth();
$parse = new AllParse();
?>
<?php foreach($comments as $row) : ?>
    <div class="borderkis padkis">
        <h2 class="noblock">Тема: <a href="/viewugthread_<?= $row['ugthread_id']; ?>"><?= html($row['ugt_title']); ?></a></h2>
        <span class="count"> сообщений:<?= $row['cnt']; ?></span>
        <br><br>
        <table>
            <tr>
                <td valign="top" width="100">
                    <div class="avatar" style="background-image: url(<?php echo avatar($myrow, $row['commpic1'], $row['commphoto_visibility']); ?>);"></div>
                    <a href="/id<?= $row['commid']; ?>" class="hover-tip" target="_blank">
                        <img src="/img/info_small_<?= $row['commgender']; ?>.png" width="15" height="14">
                    </a>
                    <a href="/id<?= $row['commid']; ?>" target="_blank"><b><?= $row['commlogin']; ?></b></a>
                    <br>
                    <?= $row['ugc_date']; ?>
                </td>
                <td>
                    <?= nl2br(nickart(imgart(smile($parse->parse($row['ugc_text']))))); ?>
                    <br>
                </td>
            </tr>
        </table>
        <p><?php if((int)$row['ug_hidden'] === 1) : ?><span class="red">Закрытая группа:</span><?php else: ?>Групппа:<?php endif; ?>
            <a href="/groups/<?= $row['ugroup_id']; ?>" target="_blank"><?= $row['ug_title']; ?></a>
        </p>
        <br>
    </div>
<?php endforeach; ?>