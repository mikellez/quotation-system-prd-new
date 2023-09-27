<?php

namespace backend\controllers;

use Yii;
use common\models\PointLedger;
use common\models\search\PointLedgerSearch;
use common\models\Point;
use common\models\search\PointSearch;
use common\models\User;
use common\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PointTransferController implements the CRUD actions for PointLedger model.
 */
class PointTransferController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PointLedger models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PointLedgerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['type'=>'EAT']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PointLedger model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PointLedger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PointLedger();
        $model->type = 'EAT';
        $model->action = 'INS';
        $model->credit = 0.00;

        if (Yii::$app->request->post()) {
            $user_to = User::findOne(Yii::$app->request->post('PointLedger')['user_id_to']);
            $user_to_point = Point::findOne(['user_id'=>Yii::$app->request->post('PointLedger')['user_id_to']]);
            $user_from = User::findOne(Yii::$app->request->post('PointLedger')['user_id_from']);
            $user_from_point = Point::findOne(['user_id'=>Yii::$app->request->post('PointLedger')['user_id_from']]);

            $model->user_id = Yii::$app->request->post('PointLedger')['user_id_from'];
            $model->user_id_from = null;
            $model->user_id_to = Yii::$app->request->post('PointLedger')['user_id_to'];
            $model->credit = Yii::$app->request->post('PointLedger')['credit'];
            $model->debit = 0;
            $model->balance = $model->debit - $model->credit;
            $model->remark = Yii::$app->request->post('PointLedger')['remark'];
            $model->description = 'Transfer to '.$user_to->username;
            $model->accumulate_point = $user_from_point->balance + $model->balance;
            $model->save();

            $model2 = new PointLedger();
            $model2->attributes = $model->attributes;
            $model2->user_id = Yii::$app->request->post('PointLedger')['user_id_to'];
            $model2->user_id_from = Yii::$app->request->post('PointLedger')['user_id_from'];
            $model2->user_id_to = null;
            $model2->credit = 0;
            $model2->debit = Yii::$app->request->post('PointLedger')['credit'];
            $model2->balance = $model2->debit - $model2->credit;
            $model2->remark = Yii::$app->request->post('PointLedger')['remark'];
            $model2->description = 'Transfer from '.$user_from->username;
            $model2->accumulate_point = $user_to_point->balance + $model2->balance;
            $model2->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PointLedger model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PointLedger model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PointLedger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PointLedger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PointLedger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
