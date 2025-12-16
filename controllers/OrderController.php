<?php

namespace app\controllers;

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
}
