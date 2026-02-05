<?php

namespace app\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

use app\models\Documents\Decrease\DecreaseItem;
use app\models\Documents\Decrease\DecreaseItemSearch;

class DecreaseItemController extends BaseCrudController
{

    protected function getModel()
    {
        return new DecreaseItem();
    }

    protected function getSearchModel()
    {
        return new DecreaseItemSearch();
    }

    protected function getTwoId()
    {
        return ['decrease_id', 'assortment_id'];
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
                        $this->redirect(['/decrease/edit/' . $model->decrease_id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('edit', compact('model'));
        }

        return $this->renderAjax('edit', compact('model'));
    }
}