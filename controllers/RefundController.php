<?php

namespace app\controllers;

use yii\web\Response;

use app\models\Documents\Order\Order;
use app\models\Documents\Refund\Refund;
use app\models\Documents\Refund\RefundItemSearch;
use app\models\Documents\Refund\RefundSearch;

class RefundController extends BaseCrudController
{

    protected function getModel()
    {
        return new Refund();
    }

    protected function getSearchModel()
    {
        return new RefundSearch();
    }

    protected function getTwoId()
    {
    }


    public function actionCreate()
    {
        $model = new Refund();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['refund/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new RefundItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    // При выборе Типа возврата
    public function actionChangeType()
    {
        $type_id = \Yii::$app->request->post('type_id');
        $company_id = \Yii::$app->request->post('company_id');
        $stock_id = \Yii::$app->request->post('stock_id');
        $executor_id = \Yii::$app->request->post('executor_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $docList = [];
        switch ($type_id) {
            case Order::TYPE_STOCK:
                // Возврат по заказу со склада
                $docList = Order::getListForRefund($type_id, $company_id, $stock_id, null);
                break;
            case Order::TYPE_EXECUTOR:
                // Возврат по прямому заказу
                $docList = Order::getListForRefund($type_id, $company_id, null, $executor_id);
                break;
        }

        return $docList;
    }

    // При выборе Старшего документа
    public function actionChangeOrder()
    {
        $type_id = \Yii::$app->request->post('type_id');
        $order_id = \Yii::$app->request->post('order_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $company_own_id = null;
        $order = Order::findOne($order_id);
        $company_own_id = $order?->company_own_id;
        $stock_id = $order?->stock_id;
        $executor_id = $order?->executor_id;

        return [
            'company_own_id' => $company_own_id,
            'stock_id' => $stock_id,
            'executor_id' => $executor_id
        ];
    }
}