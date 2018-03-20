<?php
/**
* @var array $diary
*/
?>
<h2><?php echo !empty($diary) ? 'Редактировать' : 'Добавить' ;?> дневник</h2>
<form method="post">
    <label for="title">Заголовок:</label>
    <br>
    <input id="title" style="width: 90%; border: 1px solid #888; padding: 5px;"
        value="<?php if(!empty($diary['title_di'])) : ?><?php echo html($diary['title_di']); ?><?php endif; ?>"
        type="text" name="title" required>
    <br>
    <label for="editor">Текст:</label>
    <br>
    <textarea
        style="width: 90%; height: 150px; border: 1px solid #888;padding: 5px;"
        name="text"
        id="editor"><?php if(!empty($diary['text_di'])) : ?><?php echo $diary['text_di']; ?><?php endif; ?></textarea>
    <br>
    <button class="btn btn-primary" type="submit"><?php echo !empty($diary) ? 'Сохранить' : 'Опубликовать'; ?></button>
</form>