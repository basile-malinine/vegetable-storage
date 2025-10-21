<?php

namespace app\controllers;

use Yii;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User\LoginForm;
use app\models\User\User;
use app\models\User\UserSearch;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['user.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['user.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['user.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['user.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Пользователи';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new User();

        $header = 'Пользователь (новый)';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model', 'header']));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $header = 'Пользователь [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $dbMessages = \Yii::$app->params['messages']['db'];
        try {
            $model->delete();
        } catch (IntegrityException $e) {
            \Yii::$app->session->setFlash('error', $dbMessages['delIntegrityError']);
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', $dbMessages['delError']);
        }

        return $this->redirect(['index']);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    private function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function postRequestAnalysis($model): bool
    {
        if ($model->load($this->request->post())) {
            if ($model->validate() && $model->save()) {
                return true;
            }
        }
        return false;
    }
}