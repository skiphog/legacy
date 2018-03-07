<?php
/**
* @var array $groups
*/
?>
<?php foreach($groups as $group) : ?>
<div class="ug-list ug-h120 ug-block">
    <div class="ug-inner">
        <img class="avatar120" src="https://swing-kiska.ru/avatars/group_thumb/<?php echo $group['ug_avatar']; ?>">
        <div class="ug-title">
            <h2 class="noblock"><a href="/viewugroup_<?php echo $group['ugroup_id']; ?>"><?php echo html($group['ug_title']); ?></a></h2>
            <div class="ug-controls">
                <span class="ug-usercount">участников: <?php echo $group['ucnt'] ?: 1; ?></span>
                <?php if((int)$group['ug_hidden'] === 1) : ?>
                    <span class="red">(закрытая группа)</span>
                <?php else : ?>
                    <span class="green">(открытая группа)</span>
                <?php endif; ?>
            </div>
        </div>
        <br>
        <div class="ug-descr">
            <p><?php echo subText(nl2br(hyperlink($group['ug_descr'])), 700,'...')?></p>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
<?php endforeach; ?>
