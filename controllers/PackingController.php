<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

use app\models\Assortment\Assortment;
use app\models\Documents\Packing\Packing;
use app\models\Documents\Packing\PackingItemSearch;
use app\models\Documents\Packing\PackingSearch;

class PackingController extends BaseCrudController
{

    protected function getModel()
    {
        return new Packing();
    }

    protected function getSearchModel()
    {
        return new PackingSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Packing();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['packing/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new PackingItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if (!$model->date_close) {
                    $session = Yii::$app->session;
                    if ($session->has('packing.old_values')) {
                        $session->remove('packing.old_values');
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
