<?php

namespace app\modules\page\controllers;

use app\modules\page\models\Page;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class FrontendController
 * @package app\modules\page\controllers
 */
class FrontendController extends Controller
{
    /**
     * @param $alias
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($alias)
    {
        $model = $this->findPage($alias);
        $parents = $model->parents()->all();

        return $this->render('view', compact('model', 'parents'));
    }

    /**
     * @param $alias
     *
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findPage($alias)
    {
        if (($model = Page::findOne(['alias' => $alias])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}