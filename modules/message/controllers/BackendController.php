<?php

namespace app\modules\message\controllers;

use app\modules\message\models\Message;
use app\modules\message\models\MessageSearch;
use Yii;
use app\modules\admin\components\BalletController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * BackendController allows you to administrate messages.
 *
 * Class BackendController
 * @package app\modules\message\controllers
 */
class BackendController extends BalletController
{
    /**
     * Lists all models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject(MessageSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', compact('dataProvider', 'searchModel'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject([
            'class' => Message::className(),
            'scenario' => 'create',
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('message', 'Message has been created'));

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', compact('model'));
    }

    /**
     * Updates an existing model.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        Url::remember();
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('message', 'Message has been updated'));

            return $this->refresh();
        } else {
            return $this->render('_main', compact('model'));
        }
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', Yii::t('message', 'Message has been deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Message::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException('The requested message does not exist.');
        }

        return $model;
    }
}
