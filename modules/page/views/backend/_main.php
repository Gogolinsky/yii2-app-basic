<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \app\modules\page\models\Page $model
 * @var array $pages
 */

$this->title = $model->title ? 'Редактирование: ' . $model->title : 'Создание страницы';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('page', 'Pages'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $model->title;

?>


<?php $this->beginContent('@app/modules/page/views/backend/update.php', compact('model')); ?>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="parent" class="control-label">Родительская страница</label>
                <select class="form-control" name="parent_id" id="parent">
                    <option value="0">--Нет--</option>
                    <?php foreach ($pages as $item): ?>
                        <?php if ($item->id == $model->id) {
                            continue;
                        } ?>
                        <option
                            value="<?= $item->id; ?>" <?php if (!$model->isNewRecord && null !== $model->parents(1)->one() && $model->parents(1)->one()->id == $item->id) {
                            echo 'selected';
                        } ?>>
                            <?php for ($i = 0;
                            $i < $item->depth;
                            $i++): ?>&emsp;<?php endfor; ?>
                            <?= $item->title; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?= $form->field($model, 'title')->textInput([
                'class' => 'form-control transIt'
            ]) ?>
            <?= $form->field($model, 'alias')->textInput([
                'class' => $model->alias ? 'form-control transOff' : 'form-control transTo'
            ]) ?>
            <?= $form->field($model, 'file')->fileInput(); ?>
            <?php if (isset($model->image)): ?>
                <p><img src="<?= $model->getPathToImages($model->image); ?>"/></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                    'preset' => 'full',
                    'inline' => false,
                    'height' => '200px',
                    'allowedContent' => true,
                    'skin' => 'office2013',
                ]),
            ]); ?>
            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                    'preset' => 'full',
                    'inline' => false,
                    'height' => '700px',
                    'allowedContent' => true,
                    'skin' => 'office2013',
                ]),
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']); ?>
    </div>

<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>