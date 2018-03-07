<?php
/**
 * @var \App\Models\Myrow $myrow
 * @var \App\System\Cache $cache
 * @var PDO $dbh
 */
?>
<marquee behavior="scroll" direction="up" height="130" width="157" scrollamount="2">
    <div id="contentch">
        <?php

        if (!$minchat = $cache->get('minchat')) {
            $result = $dbh->query('select ch_nik,ch_text,ch_uid from chat order by ch_timestamp desc limit 0,5');
            if ($result->rowCount()) {
                $minchat = [];
                while ($row = $result->fetch()) {
                    $row['ch_text'] = hyperlink(smile($row['ch_text']));
                    $minchat[] = $row;
                }
                $cache->set('minchat', $minchat);
            }
        }

        if (!empty($minchat) && is_array($minchat)) {
            foreach ($minchat as $value) { ?>
                <a href="/id<?php echo $value['ch_uid']; ?>"><?php echo $value['ch_nik']; ?></a>:
                <br>
                <?php
                $value['ch_text'] = preg_replace_callback('#{{(.+?)}}#', function ($item) {
                    if (empty($_SESSION['login'])) {
                        return '<b style="color:#747474">' . $item[1] . '</b>';
                    }
                    $color = ($_SESSION['login'] === $item[1]) ? '#F00' : '#747474';

                    return '<b style="color:' . $color . '">' . $item[1] . '</b>';
                }, $value['ch_text']);
                echo nl2br($value['ch_text']);
                ?>
                <br/>
            <?php }
        } ?>
    </div>
</marquee>