<?php

namespace backend\controllers;

use Yii;
use common\models\PointDoc;
use common\models\search\PointDocSearch;
use common\models\Quotation;
use common\models\search\QuotationSearch;
use common\models\Point;
use common\models\search\PointSearch;
use common\models\PointLedger;
use common\models\search\PointLedgerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PointCollectionController implements the CRUD actions for PointDoc model.
 */
class PointCollectionController extends Controller
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
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all PointDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PointDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(!Yii::$app->user->can('view-all-point-collection')) {
            $dataProvider->query->andWhere(['=', 'user_id', Yii::$app->user->id]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PointDoc model.
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
     * Creates a new PointDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new PointDoc();

        $modelQuotation = Quotation::findOne($id);
        $modelQuotation->status = 12; //confirm
        if(!$modelQuotation->save()) {
            print_r($modelQuotation->getErrors());die;
        }

        $model->quotation_id = $id;
        $model->user_id = $modelQuotation->created_by;
        $model->user_id_from = 16;
        $model->user_id_to = $modelQuotation->created_by;
        $model->doc_no = $modelQuotation->doc_no;
        $model->total_payment_received = $modelQuotation->total_price_after_disc;


        if ($model->load(Yii::$app->request->post())) {

            $modelPoint = Point::find(['user_id'=>$modelQuotation->created_by])->one();
            $modelPointLedger = new PointLedger();

            $model->doc_type = "EAP";
            $model->sales_point_rate = 250;
            $model->total_sales_point = $modelQuotation->total_price_after_disc/$model->sales_point_rate;
            $model->total_debit_sales_point = Yii::$app->request->post('PointDoc')['total_payment_received'] / $model->sales_point_rate;
            //$model->bf = $modelPoint->balance;
            //$model->total_point = $modelPoint->balance + $model->total_debit_sales_point;
            $model->status = 12; //confirm
            $model->status_by = Yii::$app->user->id;
            $model->status_at = time();
            if(!$model->save()) {
                print_r($model->getErrors());die;
            }

            //$modelPoint->balance = $model->total_point;
            //$modelPoint->save();

            $modelPointLedger->user_id = $modelQuotation->created_by;
            $modelPointLedger->user_id_from = 16;
            $modelPointLedger->user_id_to = $modelQuotation->created_by;
            $modelPointLedger->doc_id = $modelPoint->id;
            $modelPointLedger->type = "EAP";
            $modelPointLedger->action = "INS";
            $modelPointLedger->debit = $model->total_debit_sales_point;
            $modelPointLedger->credit = 0;
            $modelPointLedger->balance = $modelPoint->balance + $model->total_debit_sales_point;
            $modelPointLedger->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdateCancel($id = null)
    {
        $model = PointDoc::findOne($id);
        $modelQuotation = Quotation::findOne($model->quotation_id);

        $modelPoint = Point::find(['user_id'=>$modelQuotation->created_by])->one();
        $modelPointLedger = new PointLedger();

        $modelPointLedger->user_id = $model->created_by;
        $modelPointLedger->user_id_from = 16;
        $modelPointLedger->user_id_to = $modelQuotation->created_by;
        $modelPointLedger->doc_id = $id;
        $modelPointLedger->type = "EAP";
        $modelPointLedger->action = "VOID";
        $modelPointLedger->debit = 0;
        $modelPointLedger->credit = $model->total_debit_sales_point;
        $modelPointLedger->balance = $modelPointLedger->debit - $modelPointLedger->credit;
        $modelPointLedger->description = "Cancel ".$modelQuotation->doc_no;
        $modelPointLedger->save();

        $model->status = PointDoc::STATUS_CANCEL;
        $model->save();

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionUpdateDone($id = null)
    {
        $model = PointDoc::findOne($id);
        $modelQuotation = Quotation::findOne($model->quotation_id);

        $modelPoint = Point::find(['user_id'=>$modelQuotation->created_by])->one();
        $modelPointLedger = new PointLedger();

        $modelPointLedger->user_id = $modelQuotation->created_by;
        $modelPointLedger->user_id_from = 16;
        $modelPointLedger->user_id_to = $modelQuotation->created_by;
        $modelPointLedger->doc_id = $id;
        $modelPointLedger->type = "EAP";
        $modelPointLedger->action = "INS";
        $modelPointLedger->debit = $model->total_debit_sales_point;
        $modelPointLedger->credit = 0;
        $modelPointLedger->balance = $modelPointLedger->debit - $modelPointLedger->credit;
        $modelPointLedger->accumulate_point = $modelPoint->balance + $modelPointLedger->balance;
        $modelPointLedger->description = $modelQuotation->doc_no;
        $modelPointLedger->save();

        $model->status = PointDoc::STATUS_DONE;
        $model->save();

        return $this->redirect(['view', 'id' => $model->id]);

    }

    /**
     * Updates an existing PointDoc model.
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
     * Deletes an existing PointDoc model.
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
     * Finds the PointDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PointDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PointDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
