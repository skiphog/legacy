<?php /** @var $data array */?>
<?php foreach ($data as $event) {?>
    <div class="events border-box">
        <div class="e-table e-table-first">
            <img class="e-ava" src="/<?= $event['img']; ?>" alt="eava">
        </div>
        <div class="e-table">
            <div class="e-mod-head">
            <?php if($_SERVER['REQUEST_TIME'] > $event['ts_b']) {?>
                <?php if($_SERVER['REQUEST_TIME'] > $event['ts_e']) {?>
                    <span class="e-mod e-mod-4">Вечеринка завершена</span>
                <?php }else{?>
                    <span class="e-mod e-mod-3">Вечеринка уже началась</span>
                <?php }?>
            <?php }?>
            </div>
            <h3><a href="/parties/<?= $event['id']; ?>"><?= html($event['title']); ?></a></h3>
            <div class="e-date">
                <span class="e-date-head"><?= date('d.m.Y',$event['ts_b']); ?> в <?= date('H:i',$event['ts_b']); ?></span>
                <i class="e-date-time">
                    <?php if($_SERVER['REQUEST_TIME'] < $event['ts_b']) {?>
                        начнется через <?php echo (new \App\Components\SwingDate($event['begin_date']))->getHumansShort(); ?>
                        (продолжительность &mdash; <?php echo (new \App\Components\SwingDate(date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'] + ($event['ts_e'] - $event['ts_b']))))->getHumansShort(); ?>)
                    <?php }elseif($_SERVER['REQUEST_TIME'] > $event['ts_e']) {?>
                        закончилась <?php echo (new \App\Components\SwingDate($event['end_date']))->getHumans(); ?>
                    <?php }else{?>
                        началась
                    <?php }?>
                </i>
            </div>
            <div class="e-addr">
                <div>
                    <span class="e-sity"><?= $event['city']; ?></span>
                    <span>(<?= html($event['club']); ?>)</span>
                </div>
                <div><?= html($event['address']); ?></div>
            </div>
        </div>
    </div>
<?php }?>