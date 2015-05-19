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
    <?php $form = ActiveForm::begin([
        'options' => [
            'onkeydown' => "if (event.ctrlKey && event.keyCode == 13) this.submit();"
        ]
    ]) ?>
        <?= $form->field($model, 'content')->label(false)->textarea() ?>
        <?= $form->field($model, 'speed')->hiddenInput() ?>
        <input class="submit" type="submit" value=">" />
    <?php $form::end()  ?>
    <div id="results">
        <?php echo $model->result ?>
    </div>
</div>
<div class="footer">
    <div class="speed">
        Speed<br />
        <?php printf("%0.7f", $model->speed) ; ?> s.<br />
        <?php printf("%0.7f", $model->oldSpeed); ?> s.(old)
    </div>
</div>

<span class="home"><a href="/">HOME</a></span>