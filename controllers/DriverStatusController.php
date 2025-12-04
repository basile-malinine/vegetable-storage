<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\DriverStatus\DriverStatus;
use app\models\DriverStatus\DriverStatusSearch;

class DriverStatusController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new DriverStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Статус Водитель';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new DriverStatus();

        $header = 'Статус Водитель (новый)';

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
        $header = 'Статус Водитель [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = DriverStatus::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
