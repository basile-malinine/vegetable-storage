<?php

namespace app\controllers;

use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Assortment\Assortment;
use app\models\Assortment\AssortmentSearch;

class AssortmentController extends Controller
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
                        'roles' => ['assortment.list'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['assortment.create'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['assortment.edit'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['assortment.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AssortmentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $header = 'Номенклатура';

        return $this->render('list', ['dataProvider' => $dataProvider, 'header' => $header]);
    }

    public function actionCreate()
    {
        $model = new Assortment();

        $header = 'Номенклатура (новая позиция)';

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
        $header = 'Номенклатура [' . $model->name . ']';

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
        if (($model = Assortment::findOne(['id' => $id])) !== null) {
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