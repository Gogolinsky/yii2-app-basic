<?php

namespace app\modules\user\widgets;

use app\modules\user\models\LoginForm;
use Yii;
use yii\base\Widget;

/**
 * Class Login
 * @package app\modules\user\widgets
 */
class Login extends Widget
{
    /** @var bool */
    public $validate = true;

    /** @inheritdoc */
    public function run()
    {
        $model = Yii::createObject(LoginForm::className());
        $action = $this->validate ? null : ['/user/security/login'];

        if ($this->validate && $model->load(Yii::$app->request->post()) && $model->login()) {
            // TODO Проверить правильность перенаправления
            //Yii::$app->response->redirect(Yii::$app->user->returnUrl);
        }

        return $this->render('login', compact('model', 'action'));
    }
}
