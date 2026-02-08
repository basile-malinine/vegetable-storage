<?php

namespace app\controllers;

use app\controllers\BaseCrudController;
use app\models\Documents\Sorting\SortingItem;
use app\models\Documents\Sorting\SortingItemSearch;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class SortingItemController extends BaseCrudController
{

    protected function getModel()
    {
        return new SortingItem();
    }

    protected function getSearchModel()
    {
        return new SortingItemSearch();
    }

    protected function getTwoId()
    {
        return ['sorting_id', 'assortment_id'];
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
                        $this->redirect(['/sorting/edit/' . $model->sorting_id]);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('edit', compact('model'));
        }

        return $this->renderAjax('edit', compact('model'));
    }
}