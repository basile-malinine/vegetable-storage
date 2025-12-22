<?php

namespace app\controllers;

use app\models\Documents\Order\OrderItem;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Order\Order;
use app\models\Documents\Order\OrderItemSearch;
use app\models\Documents\Order\OrderSearch;

class OrderController extends BaseCrudController
{

    protected function getModel()
    {
        return new Order();
    }

    protected function getSearchModel()
    {
        return new OrderSearch();
    }

    protected function getTwoId()
    {
    }

    public function actionCreate()
    {
        $model = new Order();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['order/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new OrderItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    public function actionAddOrdersToDelivery($id)
    {
        $model = Delivery::findOne($id);
        $params = [
            'type_id' => Order::TYPE_EXECUTOR,
            'supplier_id' => $model->company_own_id,
            'executor_id' => $model->executor_id,
        ];
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($params);

        return $this->renderAjax('list-delivery-links', compact('model', 'dataProvider'));
    }

    public function actionSetLinkToDelivery()
    {
        $delivery_id = \Yii::$app->request->post('delivery_id');
        $val_delivery_id = \Yii::$app->request->post('val_delivery_id');
        $id = \Yii::$app->request->post('id');

        $model = $this->findModel($id);
        $model->delivery_id = $val_delivery_id;
        if (!$val_delivery_id) {
            $item = OrderItem::findOne([
                'order_id' => $model->id,
                'assortment_id' => $model->items[0]->assortment_id,
            ]);
            $item->shipped = null;
            $item->save();
        }
        $model->save();

        $delivery = Delivery::findOne($delivery_id);
        if ($delivery) {
            $delivery->calculateShippedInOrders();
        }

        return true;
    }
}
