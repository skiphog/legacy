<?php
/**
 * @var array $paginate
 * @var string $link
 */
?>
<ul class="pagination">
    <?php foreach ($paginate as $key => $value) : ?>
        <li class="<?php echo $value; ?>"><a href="<?php echo $link, $key; ?>"><?php echo $key; ?></a></li>
    <?php endforeach; ?>
</ul>