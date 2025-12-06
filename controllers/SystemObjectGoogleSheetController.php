<?php

namespace app\controllers;

use yii\db\IntegrityException;
use yii\web\NotFoundHttpException;

use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheet;
use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheetSearch;

class SystemObjectGoogleSheetController extends BaseController
{
    public function actionIndex($id = null)
    {
        $searchModel = new SystemObjectGoogleSheetSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new SystemObjectGoogleSheet();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id, $id2)
    {
        $model = $this->findModel([$id, $id2]);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDeleteByTwoParams($id, $id2)
    {
        $model = $this->findModel([$id, $id2]);

        $dbMessages = \Yii::$app->params['messages']['db'];
        try {
            $model->delete();
        } catch (IntegrityException $e) {
            if (!$this->request->isAjax) {
                \Yii::$app->session->setFlash('error', $dbMessages['delIntegrityError']);
            }
        } catch (\Exception $e) {
            if (!$this->request->isAjax) {
                \Yii::$app->session->setFlash('error', $dbMessages['delError']);
            }
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $object_id = $id[0];
        $google_id = $id[1];

        if (($model = SystemObjectGoogleSheet::findOne(['system_object_id' => $id[0],
                'google_sheet_id' => $id[1]])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}