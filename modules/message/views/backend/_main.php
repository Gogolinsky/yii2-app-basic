<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Basic fields update
 *
 * @var $this yii\web\View
 * @var $model app\modules\message\models\Message
 */

?>

<?php $this->beginContent('@app/modules/message/views/backend/update.php', compact('model')); ?>

<?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'title')->textInput([
                'class' => 'form-control transIt'
            ]); ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'alias')->textInput([
                'class' => $model->alias ? 'form-control transOff' : 'form-control transTo'
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                    'preset' => 'full',
                    'inline' => false,
                    'height' => '400px',
                    'allowedContent' => true,
                    'skin' => 'office2013',
                ]),
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']); ?>
            </div>
        </div>
    </div>

<?php $form::end(); ?>

<?php $this->endContent(); ?>