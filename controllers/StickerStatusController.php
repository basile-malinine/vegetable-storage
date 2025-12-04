<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\StickerStatus\StickerStatus;
use app\models\StickerStatus\StickerStatusSearch;

class StickerStatusController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new StickerStatusSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new StickerStatus();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
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
        if (($model = StickerStatus::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
