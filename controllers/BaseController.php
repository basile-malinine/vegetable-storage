<?php

namespace app\controllers;

use yii\web\Controller;
use yii\db\IntegrityException;
use yii\web\Response;

abstract class BaseController extends Controller
{
    public function actionDelete($id): Response
    {
        $this->deleteById($id);

        return $this->redirect(['index']);
    }

    protected function deleteById($id): bool
    {
        $model = $this->findModel($id);

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

    abstract protected function findModel($id);
}
