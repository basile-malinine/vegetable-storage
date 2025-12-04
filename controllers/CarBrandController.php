<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\CarBrand\CarBrand;
use app\models\CarBrand\CarBrandSearch;

class CarBrandController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new CarBrandSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Марки автомобилей';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new CarBrand();

        $header = 'Марка автомобиля (новая)';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model', 'header']));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $header = 'Марка автомобиля [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = CarBrand::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
