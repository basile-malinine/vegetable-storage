<?php

namespace app\controllers;

use app\models\LegalSubject\LegalSubject;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\models\Country\Country;
use app\models\Country\CountrySearch;

class CountryController extends BaseController
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

        return $this->render('list', compact(['searchModel', 'dataProvider']));
    }

    public function actionCreate()
    {
        $model = new Country();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model'));
    }

    protected function findModel($id)
    {
        if (($model = Country::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetInnName()
    {
        $id = \Yii::$app->request->post('id');
        $typeId = \Yii::$app->request->post('typeId');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $data = '';
        switch ($typeId) {
            case LegalSubject::TYPE_COMPANY:
                $data = $model->inn_legal_name;
                break;
            case LegalSubject::TYPE_BUSINESSMAN:
            case LegalSubject::TYPE_PERSON:
                $data = $model->inn_name;
                break;
        }

        return $data;
    }

    public function actionGetAlfa2()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        return $model->alfa2;
    }
}