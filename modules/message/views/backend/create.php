<?php

/**
 * Create layout
 *
 * @var yii\web\View $this
 * @var \app\modules\message\models\Message $model
 */

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создание сообщения';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('message', 'Messages'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-10">

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
                    <?= Html::submitButton('Создать', ['class' => 'btn btn-success btn-block']); ?>
                </div>
            </div>
        </div>

        <?php $form::end(); ?>

    </div>
</div>