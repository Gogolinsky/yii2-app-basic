<?php

namespace app\modules\user\controllers;

use app\modules\user\models\Account;
use app\modules\user\models\Profile;
use app\modules\user\models\SettingsForm;
use app\modules\user\models\User;
use app\modules\user\Module;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\modules\user\traits\AjaxValidationTrait;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \app\modules\user\Module $module
 */
class SettingsController extends Controller
{
    use AjaxValidationTrait;

    /** @inheritdoc */
    public $defaultAction = 'profile';

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'disconnect'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $model = Profile::find()->where(['id' => Yii::$app->user->identity->getId()])->one();

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));

            return $this->refresh();
        }

        return $this->render('profile', compact('model'));
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     *
     * @return string|\yii\web\Response
     */
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = Yii::createObject(SettingsForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account details have been updated'));

            return $this->refresh();
        }

        return $this->render('account', compact('model'));
    }

    /**
     * Attempts changing user's password.
     *
     * @param int $id
     * @param string $code
     *
     * @return string
     *
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = User::find()->where(['id' => $id])->one();

        if ($user === null || $this->module->emailChangeStrategy == Module::STRATEGY_INSECURE) {
            throw new NotFoundHttpException();
        }

        $user->attemptEmailChange($code);

        return $this->redirect(['account']);
    }

    /**
     * Displays list of connected network accounts.
     *
     * @return string
     */
    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     *
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = Account::find()->where(['id' => $id])->one();
        if ($account === null) {
            throw new NotFoundHttpException();
        }
        if ($account->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        $account->delete();

        return $this->redirect(['networks']);
    }
}
