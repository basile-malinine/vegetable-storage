<?php

namespace app\controllers;

use app\models\Documents\Moving\Moving;
use DateTime;

use yii\web\Response;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Acceptance\AcceptanceItemSearch;
use app\models\Documents\Acceptance\AcceptanceSearch;
use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Refund\Refund;
use app\models\Remainder\Remainder;

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
            case Acceptance::TYPE_REFUND:
                $docList = Refund::getListForAcceptance();
                break;
            case Acceptance::TYPE_MOVING:
                $docList = Moving::getListForAcceptance();
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
            case Acceptance::TYPE_REFUND:
                $doc = Refund::findOne($parent_doc_id);
                $delivery_id = $doc->order->delivery_id;
                $company_own_id = $doc->company_own_id;
                $stock_id = $doc->stock_id;
                break;
            case Acceptance::TYPE_MOVING:
                $doc = Moving::findOne($parent_doc_id);
                $company_own_id = $doc->company_recipient_id;
                $stock_id = $doc->stock_recipient_id;
                break;
        }

        return [
            'delivery_id' => $delivery_id,
            'company_own_id' => $company_own_id,
            'stock_id' => $stock_id,
        ];
    }

    public function actionChangeClose()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if (!$model->date_close) {
            Remainder::addAcceptance($model);
            $model->date_close = (new DateTime('now'))->format('Y-m-d H:i');
            $model->save();
        } else {
            if (Remainder::removeAcceptance($model)) {
                $model->date_close = null;
                $model->save();
            }
        }

        $this->redirect(['acceptance/edit/' . $model->id]);
    }
}