<?php

namespace app\modules\user\controllers;

use app\modules\user\models\RecoveryForm;
use app\modules\user\models\Token;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\user\traits\AjaxValidationTrait;

/**
 * RecoveryController manages password recovery process.
 * @property \app\modules\user\Module $module
 */
class RecoveryController extends Controller
{
    use AjaxValidationTrait;

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['request', 'reset'],
                        'roles' => ['?']
                    ],
                ],
            ],
        ];
    }

    /**
     * Shows page where user can request password recovery.
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        $this->layout = '@app/modules/user/views/layouts/main';

        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var \app\modules\user\models\RecoveryForm $model */
        $model = Yii::createObject([
            'class' => RecoveryForm::className(),
            'scenario' => 'request',
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'Recovery message sent'),
                'module' => $this->module,
            ]);
        }

        return $this->render('request', compact('model'));
    }

    /**
     * Displays page where user can reset password.
     * @param int $id
     * @param string $code
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        $this->layout = '@app/modules/user/views/layouts/main';

        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = Token::find()->where(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();

        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger',
                Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));

            return $this->render('/message', [
                'title' => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        $model = Yii::createObject([
            'class' => RecoveryForm::className(),
            'scenario' => 'reset',
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'Password has been changed'),
                'module' => $this->module,
            ]);
        }

        return $this->render('reset', compact('model'));
    }
}
