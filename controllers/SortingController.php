<?php

namespace app\controllers;

use Yii;

use app\models\Documents\Sorting\Sorting;
use app\models\Documents\Sorting\SortingItemSearch;
use app\models\Documents\Sorting\SortingSearch;

class SortingController extends BaseCrudController
{

    protected function getModel()
    {
        return new Sorting();
    }

    protected function getSearchModel()
    {
        return new SortingSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Sorting();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['sorting/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new SortingItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if (!$model->date_close) {
                    $session = Yii::$app->session;
                    if ($session->has('sorting.old_values')) {
                        $session->remove('sorting.old_values');
                    }
                    return $this->render('edit', compact('model', 'dataProviderItem'));
                }
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    public function actionApply()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if ($this->postRequestAnalysis($model)) {
            $model->apply();
            $session = Yii::$app->session;
            if ($session->has('sorting.old_values')) {
                $session->remove('sorting.old_values');
            }
        }

        $this->redirect(['index']);
    }

    public function actionCancel()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $model->cancel();

        $this->redirect(['sorting/edit/' . $model->id]);
    }
}