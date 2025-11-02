<?php

namespace app\controllers;

use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Delivery\DeliveryItemSearch;
use app\models\Documents\Delivery\DeliverySearch;
use Yii;
use yii\db\IntegrityException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DeliveryController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $session = Yii::$app->session;
        if ($session->get('delivery_item.change', false)) {
            $items = $dataProvider->getModels();
            foreach ($items as $item) {
                $item->setSummaryValues();
            }
            $session->remove('delivery_item.change');
        }

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = new Delivery();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['delivery/edit/' . $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model']));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $searchModel = new DeliveryItemSearch();
        $dataProviderItem = $searchModel->search($this->request->queryParams);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'dataProviderItem'));
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $dbMessages = \Yii::$app->params['messages']['db'];
        try {
            $model->delete();
        } catch (IntegrityException $e) {
            \Yii::$app->session->setFlash('error', $dbMessages['delIntegrityError']);
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', $dbMessages['delError']);
        }

        return $this->redirect(['index']);
    }

    private function findModel($id)
    {
        if (($model = Delivery::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function postRequestAnalysis($model): bool
    {
        if ($model->load($this->request->post())) {
            if ($model->validate() && $model->save()) {
                return true;
            }
        }
        return false;
    }
}