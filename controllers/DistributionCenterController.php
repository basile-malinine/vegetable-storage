<?php

namespace app\controllers;

use yii\web\Response;

use app\models\DistributionCenter\DistributionCenter;
use app\models\DistributionCenter\DistributionCenterSearch;

class DistributionCenterController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new DistributionCenterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new DistributionCenter();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model'));
    }

    protected function findModel($id)
    {
        return DistributionCenter::findOne($id);
    }

    public function actionGetLegalSubject()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return DistributionCenter::findOne($id)->legal_subject_id;
    }
}
