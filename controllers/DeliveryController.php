<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Delivery\DeliveryItemSearch;
use app\models\Documents\Delivery\DeliverySearch;

class DeliveryController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new Delivery();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['delivery/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $searchModel = new DeliveryItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if ($model->orders) {
                    $model->calculateShippedInOrders();
                }
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    protected function findModel($id)
    {
        if (($model = Delivery::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCalc()
    {
        $id = Yii::$app->request->post('delivery_id');
        $model = Delivery::findOne($id);

        return $model->calculateShippedInOrders();
    }
}