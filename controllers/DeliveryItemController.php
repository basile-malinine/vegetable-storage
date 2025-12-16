<?php

namespace app\controllers;

use app\models\Documents\Delivery\DeliveryItemSearch;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\db\IntegrityException;
use yii\web\Controller;
use yii\web\Response;

use app\models\Documents\Delivery\DeliveryItem;

class DeliveryItemController extends BaseCrudController
{
    protected function getModel()
    {
        return new DeliveryItem();
    }

    protected function getSearchModel()
    {
        return new DeliveryItemSearch();
    }

    protected function getTwoId()
    {
        return ['delivery_id', 'assortment_id'];
    }

    public function actionAdd($id)
    {
        $model = new DeliveryItem();
        $model->delivery_id = $id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/delivery/edit/' . $id]);
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
                        $this->redirect(['/delivery/edit/' . $model->delivery_id]);
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
                return false;
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
}
