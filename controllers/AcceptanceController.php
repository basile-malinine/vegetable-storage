<?php

namespace app\controllers;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Acceptance\AcceptanceItemSearch;
use app\models\Documents\Acceptance\AcceptanceSearch;
use app\models\Documents\Delivery\Delivery;
use yii\web\Response;

class AcceptanceController extends BaseCrudController
{

    protected function getModel()
    {
        return new Acceptance();
    }

    protected function getSearchModel()
    {
        return new AcceptanceSearch();
    }

    protected function getTwoId()
    {
    }

    public function actionCreate()
    {
        $model = new Acceptance();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['acceptance/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

         return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new AcceptanceItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    // При выборе Типа приёмки
    public function actionChangeType()
    {
        $type_id = \Yii::$app->request->post('type_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $docList = [];
        switch ($type_id) {
            case Acceptance::TYPE_DELIVERY:
                $docList = Delivery::getListForAcceptance();
                break;
        }

        return $docList;
    }

    // При выборе Старшего документа
    public function actionChangeParentDoc()
    {
        $type_id = \Yii::$app->request->post('type_id');
        $parent_doc_id = \Yii::$app->request->post('parent_doc_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $delivery_id = null;
        $company_own_id = null;
        $stock_id = null;
        switch ($type_id) {
            case Acceptance::TYPE_DELIVERY:
                $delivery = Delivery::findOne($parent_doc_id);
                $delivery_id = $delivery->id;
                $company_own_id = $delivery->company_own_id;
                $stock_id = $delivery->stock_id;
                break;
        }

        return [
            'delivery_id' => $delivery_id,
            'company_own_id' => $company_own_id,
            'stock_id' => $stock_id,
        ];
    }
}