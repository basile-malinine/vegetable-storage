<?php

namespace app\controllers;

use app\models\Documents\Order\Order;
use app\models\Documents\Refund\Refund;
use app\models\Documents\Refund\RefundItemSearch;
use app\models\Documents\Refund\RefundSearch;
use yii\web\Response;

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
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $docList = [];
        switch ($type_id) {
            case Refund::TYPE_EXECUTOR:
                $docList = Order::getListForRefundExecutor();
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
        switch ($type_id) {
            case Refund::TYPE_EXECUTOR:
                $order = Order::findOne($order_id);
                $company_own_id = $order->company_own_id;
                break;
        }

        return [
            'company_own_id' => $company_own_id,
        ];
    }
}