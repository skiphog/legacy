<?php
$parse = new \Swing\Components\Parse\Clear();
?>
<style>
    .active-theme{padding:5px;margin-bottom:10px;display:block;color: inherit}
    .active-theme:hover{color:#6b676a;}
</style>
<table border="0" width="100%">
<tr>
<td valign="top">
<table width="100%" border="0">
<tr>
<td valign="top" align="left">

        <h1>Свинг знакомства на Свинг-киске. 10 лет объединяем свингеров!</h1>

        <?php if ($this->myrow->isGuest()) : ?>
            <p>
                <img src="/img/18++.jpg" align="right" alt="свинг +18" hspace="10" vspace="10"/>
                Внимание, сайт содержит материалы для взрослых. 
                Если вам нет 18 лет, если тематика сайта противоречит вашим моральным или религиозным принципам, то немедленно
                покиньте сайт.
            </p>
            <strong>Здесь Вы найдете:</strong>
            <ul>
                <li>Знакомства с семейными парами, встречи с свингерами из разных стран</li>
                <li><a href="/findlist">Поиск анкет</a> с учетом специфики свинг-знакомств</li>
                <li>Реал-статус, гарантирует знакомство с реальными свингер-парами</li>
                <li>Чат, система мгновеных сообщений, <a href="/group_result_1">свинг группы и клубы</a>, свинг
                    <a href="/diary_1">дневники</a>, "горячие" знакомства, удобный поиск по анкетам свингеров и многое другое.
                </li>
                <li>Вы самостоятельно задаете уровень доступа к личной информации.</li>
                <li>Строгая модерация.</li>
                <li>Подарки, объявления и другие функции доступны за заработанные баллы. Баллы даются за активность. Пользуйтесь всем основным функционалом БЕСПЛАТНО!</li>
            </ul>
            <h2>Пару слов о свингерах.</h2>
            <p>
                <img alt="свингеры" src="/imgart/swinger.jpg" style="float:left; margin:5px; "/>
                <strong>Свинг</strong> - взаимосогласованный обмен сексуальными партнерами между сложившимися семейными парами.
                <br>
                В настоящее время под словом свинг понимают движение, со своей философией,
                <a href="/viewstory_1363">правилами</a>,
                <a href="/group_result_1">клубами</a> и т.д. Существуют разные
                <a href="/viewstory_1787">виды свинга </a> и направления.
            </p>
            <p>
                <b>Свингеры</b> – семейные пары поддерживающие философию свинга и часто практикующие свинг. В большинстве случаев свингеры, нашедшие для себя пару, поддерживают с ней долгие отношения. Они много времени проводят вместе: отдыхают на выходных, встречаются семьями.
            </p>
            <p>
                <strong>Знакомятся свингеры</strong> на вечеринках, тематических встречах, в свингер клубах, ну и конечно же в интернете, на
                <a href="/viewstory_1930">сайтах знакомств</a>. Одним из любимых ресурсов для знакомств является данный сайт. Портал Swing-kiska.ru был основан в 2003 году, эволюционировав из обычной домашней странички. Сайт пропагандирует культуру общения свингеров, поэтому славится своим дружелюбием и приятной атмосферой. Но это не исключает строгой модерации анкет в знакомствах. Множество статей и рассказов, а так же форумы и группы про свинг, позволяют начинающим свингерам освоиться и найти ответы на все возникающие вопросы. Определить, готовы ли они к данному виду отношений и какой формат свинга подходит именно им.
            </p>
            <p>
                Так же на сайте любят знакомятся адепты близких по духу тематик. Например, любители группового секса, генг бенг отношений или поклонники
                <a href="/viewstory_1137">сексвайф</a>. На сайте каждый найдет сообщество на свой вкус. А клубы и группы позволяют объединяться с единомышленниками, участвовать в
                <a href="/partylist_1">региональных свинг вечеринках</a>, либо организовывать реальные знакомства и встречи.
            </p>
        <?php  else : ?>
            <?php
                require __DIR__ . '/baner.php';

                $global = $this->dbh->query('select site_main, theme from site where site_id = 1')->fetch();
                echo nl2br($global['site_main']), '<br>';
                $theme = unserialize($global['theme'],[]);
            ?>

            <?php foreach ((array)$theme as $value) : ?>
                <img style="vertical-align: middle;" src="<?php echo $value['image']; ?>" width="80" height="21" alt="img"/>
                <a href="<?php echo $value['link']; ?>" style="color:#F00"><?php echo $value['text']; ?></a>
                <br>
            <?php endforeach; ?>
        <?php endif;?>
    </td>
</tr>
<?php if($this->myrow->isUser()) : ?>
<!-- Новые альбомы -->
<tr>
<td>
    <?php require __DIR__ . '/new_albums.php'; ?>
</td>
</tr>
<!-- // Новые альбомы -->
<?php endif; ?>

<!-- Новые Дневники -->
<tr>
<td>
<table id="table-diary" width=100% style="border:1px solid #AAA;">
    <tr>
        <td bgcolor=#e1eaff style="border:1px solid #AAA; height: 20px; padding: 5px;">
            <h2><a href="/diary_1">Новые дневники</a></h2>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px 5px;">
            <?php
            if (!$main_diary = $this->cache->get('new_diary')) {

                $sth = $this->dbh->query('select u.id,u.login,u.gender,d.id_di,d.title_di,d.text_di from diary d join users u on u.id=d.user_di where d.main = 1 and d.deleted = 0 order by d.data_di desc limit 4');
                ob_start();
                while ($myrow_diary_m = $sth->fetch()) { ?>
                    <div class="border-box" style="padding:5px;margin-top:3px;">
                        <a class="noline hover-tip" href="id<?= $myrow_diary_m['id'] ?>" target="_blank"><img src="/img/info_small_<?php echo $myrow_diary_m['gender']; ?>.png" width="15" height="14"></a>&nbsp;
                        <b style="color:#11638C;"><?= $myrow_diary_m['login'] ?></b>
                        <br/>
                        <b style="font-size: 1.1em;"><?= subText($myrow_diary_m['title_di'], 60,
                                '&nbsp;...'); ?></b>
                        <br/>
                        <?= smile($parse->parse(subText($myrow_diary_m['text_di'], 300, '&nbsp;...'))); ?>
                        <br/>
                        <a href="viewdiary_<?= $myrow_diary_m['id_di'] ?>">В дневник &raquo;</a>
                    </div>
                <?php }
                $main_diary = ob_get_clean();

                $this->cache->set('new_diary', $main_diary);
            }
            echo $main_diary;
            ?>
        </td>
    </tr>
</table>
</td>
</tr>
<!-- //Новые Дневники -->

<!-- Новые Анкеты -->
<tr>
<td>
<?php require __DIR__ . '/new_users.php'; ?>
</td>
</tr>
<!-- //Новые Анкеты -->

<!-- Активные темы -->
<tr>
<td>
<table id="table-diary" width=100% style="border:1px solid #AAA;">
<tr>
<td bgcolor=#e1eaff style="border:1px solid #AAA; height: 20px; padding: 5px;">
<h2>Активные темы</h2>
</td>
</tr>
<tr>
<td style="padding: 10px 5px;">
    <?php
    if (!$main_tome = $this->cache->get('main_tome')) {

        $result = $this->dbh->query('select c.ugthread_id id, count(*) cnt, t.ugt_title t_title,t.ugt_descr, g.ug_title g_title 
            from ugcomments c 
            join ugthread t on t.ugthread_id = c.ugthread_id 
            join ugroup g on g.ugroup_id = t.ugroup_id 
            /*where c.ugc_date > date_sub(now(), interval 1 day)*/
            where c.ugc_date > date_sub(now(), interval 30 day)
            and c.ugc_dlt <> 1 
            and g.ugroup_id <> 32 
            and g.ug_dlt <> 1 
            and t.ugt_dlt <> 1
            and g.ug_hidden <> 1
            group by c.ugthread_id 
        order by cnt desc limit 5');
        ob_start();

        while ($row = $result->fetch()) { ?>
            <a class="border-box active-theme" href="/viewugthread_<?php echo $row['id']; ?>">
                <b style="font-size: 1.2em;"><?php echo subText($row['t_title'], 60,
                        '&nbsp;...'); ?></b>
                <br>
                <?php echo smile($parse->parse(subText($row['ugt_descr'], 300, '&nbsp;...'))); ?>
                <br>
                <em style="font-size: 1.1em"><?php echo $row['g_title']; ?></em>
            </a>
        <?php }
        $main_tome = ob_get_clean();
        $this->cache->set('main_tome', $main_tome, 1800);
    }
    echo $main_tome;
    ?>
</td>
</tr>
</table>
</td>
</tr>
<!-- //Активные темы -->

<!-- Новости -->
<tr>
<td>
<?php require __DIR__ . '/news.php' ?>
</td>
</tr>
<!-- //Новости -->
</table>
</td>

<td width="200" valign="top" align="left">
<?php require __DIR__ . '/right.php'; ?>
</td>
</tr>
</table>