<?php

namespace app\controllers;

use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\WorkType\WorkType;
use app\models\WorkType\WorkTypeSearch;

class WorkTypeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['work_type.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['work_type.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['work_type.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['work_type.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new WorkTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Виды работ';

        return $this->render('list', compact('dataProvider', 'header'));
    }

    public function actionCreate()
    {
        $model = new WorkType();

        $header = 'Вид работы (новый)';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model', 'header']));
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        $header = 'Вид работы [' . $model->name . ']';

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'header'));
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
        if (($model = WorkType::findOne(['id' => $id])) !== null) {
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