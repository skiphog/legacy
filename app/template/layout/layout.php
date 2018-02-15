<!doctype html>
<html lang="ru">
<head>
<?php require __DIR__ . '/head.php'; ?>
</head>
<body>
<?php if ($this->myrow->isHiddenImg()) :
    require __DIR__ . '/boss.php';
endif; ?>

<?php if ($this->myrow->isStels()) : ?>
    <div style="text-align: center;">
        <p style="margin: 0;font-weight: bold;font-size: 16px;background-color:#ffe4c4;">Включен режим Инкогнито</p>
    </div>
<?php endif; ?>

<table id="global-content" width="100%" cellspacing="0" cellpadding="0" border="0">
    <!-- header -->
    <tr>
        <td class="bgsat" height="340" align="left" valign="top" width="437" style="background: url('/img/backgrounds/left_<?php echo $sback; ?>.jpg') no-repeat;">
            <div id="contentcht">
                <marquee behavior="scroll" direction="up" height="130" width="157" scrollamount="2">
                    <div id="contentch">
                        <?php

                        if (!$minchat = $this->cache->get('minchat')) {
                            $result = $this->dbh->query('select ch_nik,ch_text,ch_uid from chat order by ch_timestamp desc limit 0,5');
                            if ($result->rowCount()) {
                                $minchat = [];
                                while ($row = $result->fetch()) {
                                    $row['ch_text'] = hyperlinkAll(smile($row['ch_text']));
                                    $minchat[] = $row;
                                }
                                $this->cache->set('minchat', $minchat);
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
            </div>
            <br>
            <br>
            <?php if ($this->myrow->isGuest()) { ?>
                <a href="#showimagemsg">
                    <div style="padding-left: 85px;">Войти в чат</div>
                </a>
                <div class="lightboxmsg" id="showimagemsg">
                    <p>
                        <br/>
                        <span style="font-size: 16px">Внимание!</span>
                        <br/>
                        Для полноценного использования сайта необходимо
                        <br/>
                        <br/>
                        <b><a href="/registration">зарегистрироваться</a></b>
                        <br/>
                        или авторизоваться.
                        <br/>
                        <br/>
                        <a href="#"><b>Закрыть</b></a>
                    </p>
                </div>

            <?php } else { ?>
                <div style="margin-left: 85px;"><a href="/chat">Войти в чат</a></div>
            <?php } ?>
        </td>
        <td class="bgsat" height="340" align="center" valign="top" style="background: url('/img/backgrounds/center_<?php echo $sback; ?>.jpg') repeat-x;">

            <div id="topmenu">
                <table border=0>
                    <tr>
                        <td>
                            <a href="/">
                                <div id="topmain"></div>
                            </a>
                        </td>

                        <td>
                            <a href="/findlist">
                                <div id="topmeet"></div>
                            </a>
                        </td>

                        <td>
                            <a href="/diary_1">
                                <div id="topdiary"></div>
                            </a>
                        </td>

                        <td>
                            <a href="/newugthreads_1">
                                <div id="topforum"></div>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="logo"></div>

            <?php if ($this->myrow->isUser()) {
                require __DIR__ . '/viplenta.php';
            } ?>
        </td>
        <td class="bgsat" height="340" align="left" width="38" valign="top">
            <div style="width:144px;height:340px;margin-left:-106px;background: url('/img/backgrounds/right_<?php echo $sback; ?>.jpg') no-repeat;"></div>
        </td>
    </tr>
    <!-- //header -->

    <!-- content -->
    <tr>
        <!-- left -->
        <td valign="top" width="235" align="left" style="background: url('/img/left.jpg') repeat-y;">
            <?php require __DIR__ . '/left/menu_user.php'; ?>
            <br>
            <?php if($this->myrow->isAdmin()) :
                require __DIR__ .'/left/menu_admin.php';
            endif; ?>

            <?php if($this->myrow->isModerator()) :
                require __DIR__ . '/left/menu_mod.php';
            endif; ?>

            <?php require __DIR__ . '/left/story.php'; ?>
            <br>
            <?php require __DIR__ . '/left/hot.php';?>
            <br>
            <?php require __DIR__ . '/left/stat.php';?>
            <br>
            <?php require __DIR__ . '/left/partner.php';?>
            <br>
        </td>
        <!-- //left -->

        <!-- center -->
        <td valign="top" align="left" style="padding: 10px;">

            <?php if($this->myrow->id === 3) :?>
                <?php print_r($_GET); ?>
                <br>
            <?php endif; ?>

            <div id="content" style="min-width:800px;margin-left: -210px">
                <!-- echo content -->
                <?php echo $this->content; ?>
                <!-- //content -->
            </div>
        </td>
        <!-- //center -->


        <!-- right -->
        <td valign="top" width="38" align="left" style="background: url('img/right.jpg') repeat-y;"></td>
        <!-- //right -->
    </tr>
    <!-- //content -->

    <!-- footer line -->
    <tr>
        <td colspan="3" height="2" bgcolor="#999999"></td>
    </tr>
    <!-- //footer line -->

    <!-- footer -->
    <tr>
        <td colspan="3">
            <?php require __DIR__ . '/footer.php'; ?>
        </td>
    </tr>
    <!-- //footer -->
</table>

<!-- user preloader -->
<div id="preload-users" class="border-box-tip">
    <span class="tooltip-pointer2"></span>
    <div id="response-preload-users"></div>
</div>
<!-- //user preloader -->

<!-- scripts -->
<?php require __DIR__ . '/scripts.php' ?>
<!-- //scripts -->
</body>
</html>