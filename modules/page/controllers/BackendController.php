<?php

namespace app\modules\page\controllers;

use app\modules\admin\components\BalletController;
use app\modules\page\models\Page;
use app\modules\page\models\PageSearch;
use Yii;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class BackendController
 * @package app\modules\page\controllers
 */
class BackendController extends BalletController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', compact('dataProvider', 'searchModel'));
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $pages = Page::find()->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC])->all();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $parent_id = (int)Yii::$app->request->post('parent_id');

            if ($parent_id == 0 && $model->makeRoot()) {
                return $this->redirect([
                    'update',
                    'id' => $model->id,
                    'pages' => $pages
                ]);
            } else {
                $parent = Page::findOne($parent_id);

                if ($model->appendTo($parent)) {
                    return $this->redirect([
                        'update',
                        'id' => $model->id,
                        'pages' => $pages
                    ]);
                }
            }
            VarDumper::dump($model, 10, true);
            return;
        } else {
            return $this->render('create', compact('model', 'pages'));
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $pages = Page::find()->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC])->all();

        if ($model->load(Yii::$app->getRequest()->post())) {
            $parent_post_id = (int)Yii::$app->getRequest()->post('parent_id');
            //Запоминаем текущего родителя
            $parent_current_id = $model->currentParent();
            //Проверяем, изменился ли родитель
            if ($parent_post_id != $parent_current_id) {
                if ($parent_post_id == 0) {
                    $model->makeRoot();
                } else {
                    $parent_post = $this->findModel($parent_post_id);
                    $model->appendTo($parent_post);
                }
            } else {
                $model->save();
            }

            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $parent = $model->parents(1)->one();

            return $this->render('_main', compact('model', 'parent', 'pages'));
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionSeo($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('_seo', compact('model'));
        }
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteWithChildren();

        return $this->redirect(['index']);
    }

    /**
     * Поиск модели в БД.
     * Если страница не найдена, то выкидывается NotFoundHttpException
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
