<?php

namespace app\modules\admin\components;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\modules\admin\components
 */
class BalletController extends Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
        ];
    }
}