<?php

namespace app\controllers;

use app\controllers\BaseCrudController;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\Documents\Shipment\ShipmentAcceptanceSearch;
use app\models\Remainder\Remainder;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\db\IntegrityException;
use yii\web\Response;

class ShipmentAcceptanceController extends BaseCrudController
{

    protected function getModel()
    {
        return new ShipmentAcceptance();
    }

    protected function getSearchModel()
    {
        return new ShipmentAcceptanceSearch();
    }

    protected function getTwoId()
    {
        return ['shipment_id', 'acceptance_id'];
    }

    public function actionAdd($id)
    {
        $model = new ShipmentAcceptance();
        $model->shipment_id = $id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/shipment/edit/' . $id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('add', compact('model'));
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('add', compact('model'));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id, $id2);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/shipment/edit/' . $model->shipment_id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('edit', compact('model'));
        }

        return $this->renderAjax('edit', compact('model'));
    }

    public function actionRemove()
    {
        $id = \Yii::$app->request->post('id');
        $id2 = \Yii::$app->request->post('id2');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id && $id2) {
            $model = $this->findModel($id, $id2);

            $dbMessages = \Yii::$app->params['messages']['db'];
            try {
                $model->delete();
                return true;
            } catch (IntegrityException $e) {
                if (!$this->request->isAjax) {
                    \Yii::$app->session->setFlash('error', $dbMessages['delIntegrityError']);
                }
                return false;
            } catch (\Exception $e) {
                if (!$this->request->isAjax) {
                    \Yii::$app->session->setFlash('error', $dbMessages['delError']);
                }
                return false;
            }
        }

        return false;
    }

    public function actionChangeAcceptance()
    {
        $acceptance_id = Yii::$app->request->post('acceptance_id');
        $remainder = Remainder::findOne(['acceptance_id' => $acceptance_id]);
        $data['quantity'] = $remainder->quantity;
        $data['pallet_type_id'] = $remainder->pallet_type_id;
        $data['quantity_pallet'] = $remainder->quantity_pallet;
        $data['quantity_paks'] = $remainder->quantity_paks;

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }
}