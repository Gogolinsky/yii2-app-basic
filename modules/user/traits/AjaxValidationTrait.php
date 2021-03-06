<?php

namespace app\modules\user\traits;

use yii\base\Model;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AjaxValidationTrait
 * @package app\modules\user\traits
 */
trait AjaxValidationTrait
{
    /**
     * Performs ajax validation.
     *
     * @param Model $model
     *
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }
    }
}
