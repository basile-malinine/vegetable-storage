<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\models\GoogleSheet\GoogleSheet;
use app\models\GoogleSheet\GoogleSheetSearch;

class GoogleSheetController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new GoogleSheetSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new GoogleSheet();

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
        if (($model = GoogleSheet::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTest()
    {
        $spreadsheetId = $this->request->post('spreadsheetId');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $data = GoogleSheet::testGoogleSpreadsheet($spreadsheetId);

        return $data;
    }
}
