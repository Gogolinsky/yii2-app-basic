<?php

namespace app\modules\admin\controllers;

use app\modules\admin\components\BalletController;

/**
 * Class DefaultController
 * @package app\modules\admin\controllers
 */
class DefaultController extends BalletController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
