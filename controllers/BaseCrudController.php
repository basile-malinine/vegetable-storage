<?php

namespace app\controllers;

use yii\db\IntegrityException;
use yii\web\Controller;
use yii\web\Response;

abstract class BaseCrudController extends Controller
{
    abstract protected function getModel();

    abstract protected function getSearchModel();

    abstract protected function getTwoId();

    public function actionIndex($id = null)
    {
        $searchModel = $this->getSearchModel();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', compact('dataProvider'));
    }

    public function actionCreate()
    {
        $model = $this->getModel();

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id, $id2 = null)
    {
        $model = $this->findModel($id, $id2);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDelete($id, $id2 = null): Response
    {
        $this->deleteById($id, $id2);

        return $this->redirect(['index']);
    }

    protected function deleteById($id, $id2): bool
    {
        $model = $this->findModel($id, $id2);

        $dbMessages = \Yii::$app->params['messages']['db'];
        try {
            $model->delete();
            return true;
        } catch (IntegrityException $e) {
            if (!$this->request->isAjax) {
                \Yii::$app->session->setFlash('error', $dbMessages['delIntegrityError']);
            }
            return false;
        } catch (\Exception $e) {
            if (!$this->request->isAjax) {
                \Yii::$app->session->setFlash('error', $dbMessages['delError']);
            }
            return false;
        }
    }

    // Обработка POST-запроса
    protected function postRequestAnalysis($model): bool
    {
        if ($model->load($this->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function findModel($id, $id2 = null)
    {
        if ($id2) {
            $params = $this->getTwoId();
            $model = $this->getModel()::findOne([$params[0] => $id, $params[1] => $id2]);
        } else {
            $model = $this->getModel()::findOne($id);
        }

        return $model;
    }
}