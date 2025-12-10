<?php

namespace app\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\db\IntegrityException;
use yii\web\Controller;
use yii\web\Response;

use app\models\Documents\Order\OrderItem;

class OrderItemController extends Controller
{
    public function actionAdd($id)
    {
        $model = new OrderItem();
        $model->order_id = $id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/order/edit/' . $id]);
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

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/order/edit/' . $model->order_id]);
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
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id) {
            $model = $this->findModel($id);

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

        return 'false';
    }

    protected function findModel($id)
    {
        return OrderItem::findOne($id);
    }
}