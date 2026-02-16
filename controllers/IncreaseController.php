<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Increase\Increase;
use app\models\Documents\Increase\IncreaseItemSearch;
use app\models\Documents\Increase\IncreaseSearch;

class IncreaseController extends BaseCrudController
{

    protected function getModel()
    {
        return new Increase();
    }

    protected function getSearchModel()
    {
        return new IncreaseSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }

    public function actionCreate()
    {
        $model = new Increase();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                return $this->redirect(['increase/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id);
        $searchModel = new IncreaseItemSearch();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if (!$model->date_close) {
                    return $this->redirect(['increase/edit/' . $id]);
                }
                $this->redirect(['index']);
            }
        }
        $dataProviderItem = $searchModel->search($this->request->queryParams);

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
            if ($session->has('increase.old_values')) {
                $session->remove('increase.old_values');
            }
        }

        $this->redirect(['index']);
    }

    public function actionCancel()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $model->cancel();

        $this->redirect(['increase/edit/' . $model->id]);
    }
}