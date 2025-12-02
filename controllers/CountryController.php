<?php

namespace app\controllers;

use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Country\Country;
use app\models\Country\CountrySearch;
use yii\web\Response;

class CountryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['country.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['country.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['country.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['country.delete'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['get-inn-name', 'get-alfa2'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $header = 'Страны';

        return $this->render('list', compact(['searchModel', 'dataProvider', 'header']));
    }

    public function actionCreate()
    {
        $model = new Country();
        $header = 'Страна (новая)';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model', 'header'));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $header = 'Страна [' . $model->name . ']';

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

    private function findModel($id)
    {
        if (($model = Country::findOne(['id' => $id])) !== null) {
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

    public function actionGetInnName()
    {
        $id = \Yii::$app->request->post('id');
        $isLegal = \Yii::$app->request->post('isLegal');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        return $isLegal ? $model->inn_legal_name : $model->inn_name;
    }

    public function actionGetAlfa2()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        return $model->alfa2;
    }
}