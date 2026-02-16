<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Moving\Moving;
use app\models\Documents\Moving\MovingItemSearch;
use app\models\Documents\Moving\MovingSearch;
use app\models\Documents\Remainder\Remainder;
use app\models\Stock\Stock;

class MovingController extends BaseCrudController
{
    protected function getModel()
    {
        return new Moving();
    }

    protected function getSearchModel()
    {
        return new MovingSearch();
    }

    protected function getTwoId()
    {
    }

    public function actionCreate()
    {
        $model = new Moving();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['moving/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new MovingItemSearch();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    // При выборе Приёмки
    public function actionChangeAcceptance()
    {
        $acceptance_id = \Yii::$app->request->post('acceptance_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($acceptance_id) {
            $acceptance = Acceptance::findOne($acceptance_id);
            $company_own_id = $acceptance->company_own_id;
            $stock_id = $acceptance->stock_id;
            $stock_recipient_list = Stock::getList('id <> ' . $stock_id);
            return compact(['company_own_id', 'stock_id', 'stock_recipient_list']);
        }
        $stock_recipient_list = Stock::getList();

        return compact(['stock_recipient_list']);
    }

    public function actionApply()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if ($this->postRequestAnalysis($model)) {
            $model->updateShipment();
            $session = Yii::$app->session;
            if ($session->has('moving.old_values')) {
                $session->remove('moving.old_values');
            }
        }

        $this->redirect(['moving/edit/' . $model->id]);
    }

    public function actionAddRemainder()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $shipmentAcceptance = $model->shipment->shipmentAcceptances[0];

        if (Remainder::acceptanceFromShipped($shipmentAcceptance)) {
            $model->date_close = null;
            $model->save();
        }

        $this->redirect(['moving/edit/' . $model->id]);
    }
}
