<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\GateType\GateType;
use app\models\GateType\GateTypeSearch;

class GateTypeController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new GateTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Ворота / Рампы';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new GateType();

        $header = 'Ворота / Рампы (новые)';

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
        $header = 'Ворота / Рампы [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = GateType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}