<?php

/**
 * @var app\modules\user\Module $module
 */
?>

<div class="form-box">
    <?php if ($module->enableFlashMessages): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                    <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                        <div class="alert alert-<?= $type ?>" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?= $message ?>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>
</div>