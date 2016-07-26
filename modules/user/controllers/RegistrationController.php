<?php

namespace app\modules\user\controllers;

use app\modules\page\models\Page;
use app\modules\user\models\Account;
use app\modules\user\models\RegistrationForm;
use app\modules\user\models\ResendForm;
use app\modules\user\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\user\traits\AjaxValidationTrait;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \app\modules\user\Module $module
 */
class RegistrationController extends Controller
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
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     *
     * @return string
     *
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var User $model */
        $model = Yii::createObject(RegistrationForm::className());

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        $page = Page::findOne(['alias' => 'registration-index']);

        return $this->render('register', [
            'model' => $model,
            'module' => $this->module,
            'page' => $page,
        ]);
    }

    /**
     * Displays page where user can create new account that will be connected to social account.
     *
     * @param int $account_id
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionConnect($account_id)
    {
        $account = Account::find()->where(['id' => $account_id]);

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }

        /** @var User $user */
        $user = \Yii::createObject([
            'class' => User::className(),
            'scenario' => 'connect',
        ]);

        if ($user->load(Yii::$app->request->post()) && $user->create()) {
            $account->link('user', $user);
            Yii::$app->user->login($user, $this->module->rememberFor);

            return $this->goBack();
        }

        return $this->render('connect', [
            'model' => $user,
            'account' => $account,
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
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

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $user->attemptConfirmation($code);

        return $this->render('/message', [
            'title' => Yii::t('user', 'Account confirmation'),
            'module' => $this->module,
        ]);
    }

    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     *
     * @throws \yii\web\HttpException
     */
    public function actionResend()
    {
        if ($this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $model = Yii::createObject(ResendForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->resend()) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'A new confirmation link has been sent'),
                'module' => $this->module,
            ]);
        }

        return $this->render('resend', compact('model'));
    }
}
