<?php

namespace app\controllers;

use app\models\Stock\Stock;
use app\models\Stock\StockSearch;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class StockController extends Controller
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
                        'roles' => ['stock.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['stock.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['stock.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['stock.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Склады';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new Stock();

        $header = 'Склад (новый)';

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
        $header = 'Склады [' . $model->name . ']';

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
        if (($model = Stock::findOne(['id' => $id])) !== null) {
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