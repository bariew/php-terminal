<?php
use yii\widgets\ActiveForm;
use app\models\Terminal;
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
$this->registerJsFile('@web/js/terminal.js', ['depends' => \app\assets\AppAsset::className()]);
\yii\jui\JuiAsset::register($this);
/**
 * @var Terminal $model
 */
?>
<div id="content">
    <?php $form = ActiveForm::begin([
        'options' => [
            'name' => 'terminal',
            'onkeydown' => "if (event.ctrlKey && event.keyCode == 13) $(this).submit();",
            'onsubmit' => '$.post("", $(this).serialize(), function(data){$("#results").html(data);}); return false;'
        ]
    ]) ?>
        <textarea name="Terminal[content]"  id="terminal-input"><?= $model->content ?></textarea>
        <input type="hidden" name="Terminal[speed]">
        <input class="submit" type="submit" value=">" />
    <?php $form::end()  ?>

    <input id="terminal-search"><br />
    <div id="function-doc"></div>
    <div class="clearfix"></div>
    <div id="results">
        <?php echo $model->result ?>
    </div>
</div>
<span class="home"><a href="/">HOME</a></span>