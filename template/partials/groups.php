<?php
/**
* @var array $groups
*/
$myrow = auth();
?>
<?php foreach($groups as $group) : ?>
<div class="group-row">

    <div class="group-row-info">
        <img class="group-row-avatar" src="https://swing-kiska.ru/avatars/group_thumb/<?php echo $group['ug_avatar']; ?>">
        <div class="group-row-controls">
            <div>Участников: <strong><?php echo $group['ucnt'] ?: 1; ?></strong></div>
            <div class="group-row-label">
                <?php if((int)$group['ug_hidden'] === 1) : ?>
                    <span class="red">закрытая группа</span>
                <?php else : ?>
                    <span class="green">открытая группа</span>
                <?php endif; ?>
            </div>
            <?php if((int)$group['user_id'] === $myrow->id) : ?>
                <div>
                    <a class="btn btn-success" href="/groups/<?php echo $group['ugroup_id']; ?>/edit">Управление</a>
                </div>
                <?php if(!empty($group['uncnt'])) : ?>
                <div class="border-box group-wait">
                    Ожидают модерацию:<br>
                    <strong><?php echo (int)$group['uncnt']; ?></strong>
                    <?php echo plural((int)$group['uncnt'], 'анкета|анкеты|анкет'); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="group-row-description">
        <h2 class="noblock"><a href="/groups/<?php echo $group['ugroup_id']; ?>"><?php echo html($group['ug_title']); ?></a></h2>
        <p><?php echo subText(nl2br(hyperlink($group['ug_descr'])), 700,'...')?></p>
    </div>
</div>
<?php endforeach; ?>
