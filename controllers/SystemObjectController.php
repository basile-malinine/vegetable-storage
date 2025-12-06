<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\SystemObject\SystemObject;
use app\models\SystemObject\SystemObjectSearch;

class SystemObjectController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new SystemObjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new SystemObject();

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
        if (($model = SystemObject::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}