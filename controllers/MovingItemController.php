<?php

namespace app\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

use app\models\Documents\Moving\MovingItem;
use app\models\Documents\Moving\MovingItemSearch;

class MovingItemController extends BaseCrudController
{

    protected function getModel()
    {
        return new MovingItem();
    }

    protected function getSearchModel()
    {
        return new MovingItemSearch();
    }

    protected function getTwoId()
    {
        return ['moving_id', 'assortment_id'];
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
                        $this->redirect(['/moving/edit/' . $model->moving_id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('edit', compact('model'));
        }

        return $this->renderAjax('edit', compact('model'));
    }
}