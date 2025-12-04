<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\Workshift\Workshift;
use app\models\Workshift\WorkshiftSearch;

class WorkshiftController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new WorkshiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Смены';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new Workshift();

        $header = 'Смена (новая)';

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
        $header = 'Смена [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = Workshift::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}