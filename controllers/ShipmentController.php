<?php

namespace app\controllers;

use DateTime;

use yii\helpers\ArrayHelper;
use yii\web\Response;

use app\models\Documents\Moving\Moving;
use app\models\Documents\Order\Order;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptanceSearch;
use app\models\Documents\Shipment\ShipmentSearch;
use app\models\Remainder\Remainder;

class ShipmentController extends BaseCrudController
{

    protected function getModel()
    {
        return new Shipment();
    }

    protected function getSearchModel()
    {
        return new ShipmentSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Shipment();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['shipment/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new ShipmentAcceptanceSearch();
        $dataProviderAcceptance = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderAcceptance'));
    }

    public function actionDelete($id, $id2 = null): Response
    {
        $model = $this->findModel($id);
        // Если перемещение, не удаляем
        if ($model->type_id === Shipment::TYPE_MOVING) {
            \Yii::$app->session->setFlash('error',
                'Для удаления этой Отгрузки, удалите документ на Перемещение.');
            return $this->redirect(['index']);
        }

        return parent::actionDelete($id, $id2);
    }

    // При выборе Типа приёмки
    public function actionChangeType()
    {
        $type_id = \Yii::$app->request->post('type_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $docList = [];
        switch ($type_id) {
            case Shipment::TYPE_ORDER:
                $list = Order::getList('type_id = '
                    . Order::TYPE_STOCK);
                foreach ($list as $id => $name) {
                    $order = Order::findOne($id);
                    if (!$order->shipment && $order->items) {
                        $docList[$id] = $name;
                    }
                }
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
            case Shipment::TYPE_ORDER:
                $doc = Order::findOne($parent_doc_id);
                $company_own_id = $doc->company_own_id;
                $stock_id = $doc->stock_id;
                break;
            case Shipment::TYPE_MOVING:
                $doc = Moving::findOne($parent_doc_id);
                $company_own_id = $doc->company_sender_id;
                $stock_id = $doc->stock_sender_id;
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
            foreach ($model->shipmentAcceptances as $item) {
                Remainder::shippedFromAcceptance($item);
            }
            $model->date_close = (new DateTime('now'))->format('Y-m-d H:i');
            $model->save();
            $item = $model->parentDoc->items[0];
            $item->shipped =
                array_sum(ArrayHelper::getColumn($model->shipmentAcceptances, 'quantity'));
            $item->save();
            \Yii::$app->session->setFlash('success', 'По Отгрузке произведено списание.');
        } else {
            foreach ($model->shipmentAcceptances as $item) {
                Remainder::acceptanceFromShipped($item);
            }
            $model->date_close = null;
            $model->save();
            $item = $model->parentDoc->items[0];
            $item->shipped = null;
            $item->save();
            \Yii::$app->session->setFlash('success', 'По Отгрузке произведено оприходование.');
        }

        $this->redirect(['shipment/edit/' . $model->id]);
    }
}