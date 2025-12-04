<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\FlightType\FlightType;
use app\models\FlightType\FlightTypeSearch;

class FlightTypeController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new FlightTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Типы рейсов';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new FlightType();

        $header = 'Тип рейса (новый)';

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
        $header = 'Тип рейса [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = FlightType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
