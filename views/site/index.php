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
            'onkeydown' => "if (event.ctrlKey && event.keyCode == 13) this.submit();",
            'onload' => "$('#terminal-content').focus();alert(123);"
        ]
    ]) ?>

        <textarea name="Terminal[content]" class="hide" id="terminal-content"></textarea>
        <div contenteditable="true" id="terminal-input" onkeyup="$(this).prev().text($(this).text());"
            ><?= $model->content ?></div>
        <input type="hidden" name="Terminal[speed]">
        <input class="submit" type="submit" value=">" />
    <?php $form::end()  ?>
    <div id="results">
        <?php echo $model->result ?>
    </div>
</div>
<input id="terminal-search"><br />
<div class="footer">
    <div class="speed">
        Speed<br />
        <?php printf("%0.7f", $model->speed) ; ?> s.<br />
        <?php printf("%0.7f", $model->oldSpeed); ?> s.(old)
    </div>
</div>

<span class="home"><a href="/">HOME</a></span>