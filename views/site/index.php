<?php

use yii\widgets\ActiveForm;
use app\models\Terminal;
/* @var $this yii\web\View */
$this->title = Yii::$app->name;

/**
 * @var Terminal $model
 */
?>
<div id="content">
    <input id="terminal-search" />
    <?php $form = ActiveForm::begin([
        'options' => [
            'name' => 'terminal',
            'onkeydown' => "if (event.ctrlKey && event.keyCode == 13) $(this).submit();",
            'onsubmit' => '$.post("", $(this).serialize())
                .done(function(data){$("#results").html(data);})
                .fail(function(data){$("#results").html(data.responseText);});
                return false;'
        ]
    ]) ?>
	<select name="Terminal[language]">
	<?php foreach ($model::languageList() as $language): ?>
		<option value="<?= $language ?>" <?= ($model->language==$language ? 'selected="selected"' : ""); ?>><?= $language ?></option>
	<?php endforeach; ?>
	</select>
	<textarea name="Terminal[content]"  id="terminal-input"><?= $model->content ?></textarea>
        <input type="hidden" name="Terminal[speed]" />
        <input class="submit" type="submit" value=">" />
    <?php $form::end()  ?>
    <div id="results">
        <?php echo $model->result ?>
    </div>
    <div class="clearfix"></div>
    <div id="function-doc"></div>
</div>
