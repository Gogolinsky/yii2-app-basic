<?php

namespace app\modules\user\controllers;

use app\modules\user\models\LoginForm;
use app\modules\user\Module;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use app\modules\user\traits\AjaxValidationTrait;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 */
class SecurityController extends Controller
{
    use AjaxValidationTrait;

    /**
     * @param string $id
     * @param Module $module
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'auth', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * This extension adds OpenID, OAuth and OAuth2 consumers for the Yii framework 2.0
     *
     * @return array
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => Yii::$app->user->isGuest ? [$this, 'authenticate'] : [$this, 'connect'],
            ],
        ];
    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        $this->layout = '@app/modules/user/views/layouts/main';

        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    /**
     * Tries to authenticate user via social network. If user has alredy used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *
     * @param ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
    {
        $account = forward_static_call([
            $this->module->modelMap['Account'],
            'createFromClient',
        ], $client);

        if (null === ($user = $account->user)) {
            $this->action->successUrl = Url::to([
                '/user/registration/connect',
                'account_id' => $account->id,
            ]);
        } else {
            Yii::$app->user->login($user, $this->module->rememberFor);
        }
    }

    /**
     * Tries to connect social account to user.
     *
     * @param ClientInterface $client
     */
    public function connect(ClientInterface $client)
    {
        forward_static_call([
            $this->module->modelMap['Account'],
            'connectWithUser',
        ], $client);
        $this->action->successUrl = Url::to(['/user/settings/networks']);
    }
}
