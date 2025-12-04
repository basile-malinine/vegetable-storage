<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;

use app\models\PalletType\PalletType;
use app\models\PalletType\PalletTypeSearch;

class PalletTypeController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new PalletTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Типы паллет';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new PalletType();

        $header = 'Тип паллета (новый)';

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
        $header = 'Тип паллета [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
    }

    protected function findModel($id)
    {
        if (($model = PalletType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
