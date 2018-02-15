<div style="float: left;margin-right: 5px;">
    <?php
    $baner = $this->dbh->query('select img,link,target from banners where activity = \'1\' order by RAND() limit 1')->fetch();

    if(!empty($baner)) {
        if (empty($baner['link'])) { ?>
            <img class="border-box" style="padding: 0;" src="<?php echo $baner['img']; ?>" width="300" height="248" alt="board"/>
        <?php } else {
            ?>
            <a href="<?= $baner['link']; ?>" target="<?= $baner['target'] ?>">
                <img class="border-box" style="padding: 0;" src="<?php echo $baner['img']; ?>" width="300" height="248" alt="board"/>
            </a>
        <?php }
    }?>
</div>