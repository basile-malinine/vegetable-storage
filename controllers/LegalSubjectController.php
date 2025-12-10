<?php

namespace app\controllers;

use app\models\DistributionCenter\DistributionCenter;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\models\LegalSubject\LegalSubject;
use app\models\LegalSubject\LegalSubjectSearch;

class LegalSubjectController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'all', 'supplier', 'buyer'],
                        'roles' => ['legal_subject.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['legal_subject.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['legal_subject.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['legal_subject.delete'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['get-distribution-center'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $action = yii::$app->session->get('legal-subject.list', 'all');
        $this->redirect([$action]);
    }

    public function actionAll(): string
    {
        $searchModel = new LegalSubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['is_own' => false]);

        $session = yii::$app->session;
        $session->set('legal-subject.list', 'all');

        return $this->render('list', compact(['searchModel', 'dataProvider']));
    }

    public function actionSupplier(): string
    {
        $searchModel = new LegalSubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['is_own' => false, 'is_supplier' => true]);

        $session = yii::$app->session;
        $session->set('legal-subject.list', 'supplier');

        return $this->render('list', compact(['searchModel', 'dataProvider']));
    }

    public function actionBuyer(): string
    {
        $searchModel = new LegalSubjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['is_own' => false, 'is_buyer' => true]);

        $session = yii::$app->session;
        $session->set('legal-subject.list', 'buyer');

        return $this->render('list', compact(['searchModel', 'dataProvider']));
    }

    public function actionCreate()
    {
        $model = new LegalSubject();
        $model->is_own = false;

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
        if (($model = LegalSubject::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetDistributionCenter()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id) {
            $model = LegalSubject::findOne($id);
            $children = ArrayHelper::map($model->distributionCenter, 'id', 'name');
            asort($children);
        } else {
            $children = DistributionCenter::getList();
        }

        $ret = [];
        foreach ($children as $key => $child) {
            $ret[] = ['value' => $key, 'text' => $child];
        }

        return $ret;
    }
}
