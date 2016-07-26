<?php

namespace app\modules\user\controllers;

use app\modules\admin\components\BalletController;
use app\modules\user\models\AdminAddForm;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\Module;
use Yii;
use yii\base\Module as Module2;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AdminController allows you to administrate users.
 *
 * @property Module $module
 */
class AdminController extends BalletController
{
    /**
     * @param string $id
     * @param Module2 $module
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
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'switch-block' => ['post'],
                    'switch-confirm' => ['post'],
                ],
            ],
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

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel = Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', compact('dataProvider', 'searchModel'));
    }

    /**
     * Lists all site's admins.
     *
     * @return mixed
     */
    public function actionAdmins()
    {
        Url::remember();
        $users = User::find()->all();
        $auth = Yii::$app->authManager;
        $modelsTemp = [];
        foreach ($users as $user) {
            if ($auth->checkAccess($user->id, 'administrator')) {
                $modelsTemp[] = $user->id;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['id' => $modelsTemp]),
        ]);

        $adminAddForm = Yii::createObject(AdminAddForm::className());
        $emails = ArrayHelper::map(User::find()->all(), 'email', 'email');

        if ($adminAddForm->load(Yii::$app->request->post()) && $adminAddForm->add()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been administrated'));

            return $this->refresh();
        } else {
            return $this->render('admins', compact('dataProvider', 'adminAddForm', 'emails'));
        }
    }

    /**
     * Delete admin's role from user
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDeleteAdmin($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not remove your own account'));
        } else {
            $this->findModel($id)->deleteAdmin();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has lost admin\'s role'));
        }

        return $this->redirect(['admins']);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
        ]);

        if ($user->load(Yii::$app->request->post()) && $user->create()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been created'));

            return $this->redirect(['update', 'id' => $user->id]);
        }

        return $this->render('create', compact('user'));
    }

    /**
     * Updates an existing User model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));

            return $this->refresh();
        }

        return $this->render('_account', compact('user'));
    }

    /**
     * Updates an existing profile.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdateProfile($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $profile = $user->profile;

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Profile details have been updated'));

            return $this->refresh();
        }

        return $this->render('_profile', compact('user', 'profile'));
    }

    /**
     * Confirm the User
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionConfirm($id)
    {
        $this->findModel($id)->confirm();
        Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been confirmed'));

        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Deletes administrator role from User model.
     * If deletion is successful, the browser will be redirected to the 'admins' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not remove your own account'));
        } else {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been deleted'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Block/unblock the user
     *
     * @throws NotFoundHttpException
     */
    public function actionSwitchBlock()
    {
        $id = Yii::$app->request->post('id');

        if ($id != Yii::$app->user->getId()) {
            $model = $this->findModel($id);

            if ($model->getIsBlocked()) {
                $model->unblock();
            } else {
                $model->block();
            }
        }
    }

    /**
     * Confirm/unconfirm the user
     *
     * @throws NotFoundHttpException
     */
    public function actionSwitchConfirm()
    {
        $id = Yii::$app->request->post('id');

        if ($id != Yii::$app->user->getId()) {
            $model = $this->findModel($id);

            if (!$model->getIsConfirmed()) {
                $model->confirm();
            }
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $user = User::find()->where(['id' => $id])->one();
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        return $user;
    }
}
