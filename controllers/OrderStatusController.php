<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\models\GoogleBase;
use app\models\OrderStatus\OrderStatus;
use app\models\OrderStatus\OrderStatusSearch;

class OrderStatusController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new OrderStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new OrderStatus();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
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
        if (($model = OrderStatus::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGoogleUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new OrderStatus();
        $data = GoogleBase::updateGoogleSheet($model);

        return $data;
    }
}
