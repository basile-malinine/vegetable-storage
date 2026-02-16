<?php

namespace app\controllers;

use app\models\Assortment\Assortment;
use Yii;
use yii\web\Response;

use app\models\Documents\Merging\Merging;
use app\models\Documents\Merging\MergingSearch;
use app\models\Documents\Merging\MergingItemSearch;

class MergingController extends BaseCrudController
{

    protected function getModel()
    {
        return new Merging();
    }

    protected function getSearchModel()
    {
        return new MergingSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Merging();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['merging/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new MergingItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if (!$model->date_close) {
                    $session = Yii::$app->session;
                    if ($session->has('merging.old_values')) {
                        $session->remove('merging.old_values');
                    }
                    return $this->render('edit', compact('model', 'dataProviderItem'));
                }
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    public function actionChangeAssortment()
    {
        $assortmentId = \Yii::$app->request->post('assortment_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $info = '';
        if ($assortmentId) {
            if ($assortment = Assortment::findOne($assortmentId)) {
                $info = $assortment->unit->name . ' / ' . $assortment->weight;
            }
            return $info;
        }

        return '';
    }

    public function actionClose()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->close();

        return $this->redirect(['index']);
    }

    public function actionOpen()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->open();

        return $this->redirect(['index']);
    }
}