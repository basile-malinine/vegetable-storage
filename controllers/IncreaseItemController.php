<?php

namespace app\controllers;

use app\models\Documents\Increase\IncreaseItem;
use app\models\Documents\Increase\IncreaseItemSearch;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class IncreaseItemController extends BaseCrudController
{

    protected function getModel()
    {
        return new IncreaseItem();
    }

    protected function getSearchModel()
    {
        return new IncreaseItemSearch();
    }

    protected function getTwoId()
    {
        return ['increase_id', 'assortment_id'];
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
                        $this->redirect(['/increase/edit/' . $model->increase_id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('edit', compact('model'));
        }

        return $this->renderAjax('edit', compact('model'));
    }
}