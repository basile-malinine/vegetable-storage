<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\TemperatureRegime\TemperatureRegime;
use app\models\TemperatureRegime\TemperatureRegimeSearch;

class TemperatureRegimeController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new TemperatureRegimeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Температурные режимы';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new TemperatureRegime();

        $header = 'Температурный режим (новый)';

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
        $header = 'Температурный режим [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = TemperatureRegime::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}