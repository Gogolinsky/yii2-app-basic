<?php

namespace app\modules\settings\controllers;

use app\modules\admin\components\BalletController;
use app\modules\settings\models\Settings;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class BackendController
 * @package app\modules\settings\controllers
 */
class BackendController extends BalletController
{
    /**
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel(1);

        if ($model->load(Yii::$app->request->post())) {
            $model->save();

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', compact('model'));
        }
    }

    /**
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested item does not exist.');
        }
    }
}
