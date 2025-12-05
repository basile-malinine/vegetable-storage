<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\models\Assortment\AssortmentGroup;
use app\models\Assortment\AssortmentGroupSearch;

class AssortmentGroupController extends BaseController
{
    public function actionIndex($parent_id = null)
    {
        $searchModel = new AssortmentGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', compact(['searchModel', 'dataProvider', 'parent_id']));
    }

    public function actionCreate($parent_id = null)
    {
        $model = new AssortmentGroup();
        if ($parent_id) {
            $model->parent_id = $parent_id;
        }

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if ($parent_id) {
                    $this->redirect(['assortment-group/index/' . $parent_id]);
                } else {
                    $this->redirect(['assortment-group/edit/' . $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact(['model', 'parent_id']));
    }

    public function actionEdit($id, $parent_id = null)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($this->postRequestAnalysis($model)) {
                if ($parent_id) {
                    $this->redirect(['assortment-group/index/' . $parent_id]);
                } else {
                    $this->redirect(['index']);
                }
            }
        }

        return $this->render('edit', compact(['model', 'parent_id']));
    }

    public function actionDelete($id, $parent_id = null): Response
    {
        $this->deleteById($id);

        return $this->redirect(['assortment-group/index/' . $parent_id ?: '']);
    }

    protected function findModel($id)
    {
        if (($model = AssortmentGroup::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetChild()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id) {
            $model = AssortmentGroup::findOne($id);
            $children = ArrayHelper::map($model->child, 'id', 'name');
            asort($children);
        } else {
            $children = AssortmentGroup::getChildList();
        }

        $ret = [];
        foreach ($children as $key => $child) {
            $ret[] = ['value' => $key, 'text' => $child];
        }

        return $ret;
    }

    public function actionGetParent()
    {
        $id = \Yii::$app->request->post('id');
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return AssortmentGroup::findOne($id)->parent_id;
    }
}
