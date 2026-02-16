<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Decrease\Decrease;
use app\models\Documents\Decrease\DecreaseItemSearch;
use app\models\Documents\Decrease\DecreaseSearch;

class DecreaseController extends BaseCrudController
{

    protected function getModel()
    {
        return new Decrease();
    }

    protected function getSearchModel()
    {
        return new DecreaseSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Decrease();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['decrease/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new DecreaseItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if (!$model->date_close) {
                    $session = Yii::$app->session;
                    if ($session->has('decrease.old_values')) {
                        $session->remove('decrease.old_values');
                    }
                    return $this->render('edit', compact('model', 'dataProviderItem'));
                }
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    // При выборе Приёмки
    public function actionChangeAcceptance()
    {
        $acceptance_id = \Yii::$app->request->post('acceptance_id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($acceptance_id) {
            $acceptance = Acceptance::findOne($acceptance_id);
            $company_own_id = $acceptance->company_own_id;
            $stock_id = $acceptance->stock_id;
            return compact(['company_own_id', 'stock_id']);
        }

        return compact([]);
    }

    public function actionApply()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);

        if ($this->postRequestAnalysis($model)) {
            $model->apply();
            $session = Yii::$app->session;
            if ($session->has('decrease.old_values')) {
                $session->remove('decrease.old_values');
            }
        }

        $this->redirect(['index']);
    }

    public function actionRevertRemainder()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $model->revertRemainder();

        $this->redirect(['decrease/edit/' . $model->id]);
    }
}