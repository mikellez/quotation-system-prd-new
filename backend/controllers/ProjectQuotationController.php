<?php

namespace backend\controllers;

use Yii;
use common\models\Quotation;
use common\models\search\QuotationSearch;
use common\models\Company;
use common\models\search\CompanySearch;
use common\models\User;
use common\models\search\UserSearch;
use common\models\Client;
use common\models\search\ClientSearch;
use common\models\Product;
use common\models\search\ProductSearch;
use common\models\QuotationItem;
use common\models\search\QuotationItemSearch;
use common\models\AuthAssignment;
use common\models\search\AuthAssignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\FileHelper;

/**
 * QuotationController implements the CRUD actions for Quotation model.
 */
class ProjectQuotationController extends Controller
{

    private $defaultCss = " body {font-family: sans-serif;
            font-size: 7pt;
        }
        p {	margin: 0pt; }
        table.items {
            /*border: 1px dotted #000000;*/
        }
        td { /*vertical-align: top;*/ }
        .items td {
            border-left: 1px dotted #000000;
            border-right: 1px dotted #000000;
        }
        table thead td { 
            background-color: rgb(214, 252, 208);
            text-align: center;
            border: 1px dotted #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            /*border: 1px dotted #000000;*/
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 1px dotted #000000;
            border-right: 1px dotted #000000;
        }
        .items td.totals {
            text-align: right;
            border: 1px dotted #000000;
            border-bottom: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            background-color: rgb(242, 242, 242);
        }
        .items td.cost {
            text-align: "." center;
        }
        .items td.total {
            background-color: rgb(216, 252, 219) !important;
        }
        ";

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
     * Lists all Quotation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuotationSearch();
        $searchModelDraft = new QuotationSearch();
        $dataProviderAll = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderAll->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderAll->setPagination(['pageSize' => 10]);
        //$dataProviderAll->query->andWhere(['=', 'master', 0]);
        $dataProviderAll->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderAll->query->andWhere(['=', 'active', 1]);

        $dataProviderApprove = $searchModelDraft->search(Yii::$app->request->queryParams);
        $dataProviderApprove->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderApprove->setPagination(['pageSize' => 10]);
        //$dataProviderApprove->query->andWhere(['=', 'master', 0]);
        $dataProviderApprove->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderApprove->query->andWhere(['=', 'active', 1]);

        $dataProviderDraft = $searchModelDraft->search(Yii::$app->request->queryParams);
        $dataProviderDraft->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderDraft->setPagination(['pageSize' => 10]);
        //$dataProviderDraft->query->andWhere(['=', 'master', 0]);
        $dataProviderDraft->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderDraft->query->andWhere(['=', 'active', 1]);

        $dataProviderPending = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderPending->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderPending->setPagination(['pageSize' => 10]);
        //$dataProviderPending->query->andWhere(['=', 'master', 0]);
        $dataProviderPending->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderPending->query->andWhere(['=', 'active', 1]);

        $dataProviderConfirm = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderConfirm->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderConfirm->setPagination(['pageSize' => 10]);
        //$dataProviderConfirm->query->andWhere(['=', 'master', 0]);
        $dataProviderConfirm->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderConfirm->query->andWhere(['=', 'active', 1]);

        $dataProviderDone = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderDone->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderDone->setPagination(['pageSize' => 10]);
        //$dataProviderDone->query->andWhere(['=', 'master', 0]);
        $dataProviderDone->query->andWhere(['=', 'doc_type', 'project']);
        $dataProviderDone->query->andWhere(['=', 'active', 1]);

        $dataProviderApprove->query->andWhere(['=', 'status', Quotation::STATUS_APPROVE]);
        $dataProviderDraft->query->andWhere(['=', 'status', Quotation::STATUS_DRAFT]);
        $dataProviderPending->query->andWhere(['=', 'status', Quotation::STATUS_PENDING]);
        $dataProviderConfirm->query->andWhere(['=', 'status', Quotation::STATUS_CONFIRM]);
        $dataProviderDone->query->andWhere(['=', 'status', Quotation::STATUS_DONE]);

        if(!Yii::$app->user->can('view-all-quotation')) {
            $dataProviderAll->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
            $dataProviderApprove->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
            $dataProviderDraft->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
            $dataProviderPending->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
            $dataProviderConfirm->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
            $dataProviderDone->query->andWhere(['=', 'created_by', Yii::$app->user->id]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderAll' => $dataProviderAll,
            'dataProviderApprove' => $dataProviderApprove,
            'dataProviderDraft' => $dataProviderDraft,
            'dataProviderPending' => $dataProviderPending,
            'dataProviderConfirm' => $dataProviderConfirm,
            'dataProviderDone' => $dataProviderDone
        ]);
    }

    /**
     * Displays a single Quotation model.
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
     * Creates a new Quotation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Quotation();
        $clientModel = new Client();
        $user = User::findOne(Yii::$app->user->id);
        $model->payment_tnc = Company::findOne($user->company)->payment_tnc;
        $model->user_company = $user->company;

        if ($model->load(Yii::$app->request->post())) {
            //$model->client = Yii::$app->request->post('Client')['company'];
            $model->status = $model::STATUS_DRAFT;
            $model->doc_type = 'project';
            $model->save();
            
            return $this->redirect(['item', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'clientModel' => $clientModel,
        ]);
    }

    /**
     * Creates a new revision of Quotation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRevision($id)
    {
        $model = Quotation::findOne($id);
        $newModel = new Quotation;
        $newModel->attributes = $model->attributes;
        $newModel->client = $model->client;
        $newModel->doc_no = "";
        $newModel->status = $model::STATUS_DRAFT;
        $newModel->rev_no = 1;
        $newModel->save();

        $modelItems = $model->item;
        foreach($modelItems as $modelItem) {
            $newmodelItem = new QuotationItem;
            $newmodelItem->attributes = $modelItem->attributes;
            $newmodelItem->quotation_id = $newModel->id;
            $newmodelItem->save();
        }

        return $this->redirect(['view', 'id' => $newModel->id]);
    }

    /**
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $clientModel = $this->findClientModel($model->client);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['item', 'id' => $model->id]);
        }

        if(Yii::$app->request->isAjax) {
            return $this->renderPartial('_form2', [
                'model' => $model,
                'clientModel' => $clientModel,
                'isSlave' => $model->slave
            ]);

        }

        return $this->render('update', [
            'model' => $model,
            'clientModel' => $clientModel,
            'isSlave' => $model->slave
        ]);
    }

    /**
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);
        $clientModel = $this->findClientModel($model->client);
        $searchModel = new QuotationItemSearch();
        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $authassignmentModel = $this->findAuthAssignmentModel($model->created_by);

            $content = $this->renderPartial('document_pdf', [
                        'model' => $model,
                        'authassignmentModel'=>$authassignmentModel
                        ]);

            $pdf = new \kartik\mpdf\Pdf([
                'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
                'format' => \kartik\mpdf\Pdf::FORMAT_A4,
                'content' => $content,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                //'cssFile' => '@vendor/npm-asset/bootstrap/dist/css/bootstrap.css',
                'cssInline' => $this->defaultCss,
                'options' => [
                    'title' => $model->doc_no,
                    'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
                ],
                'methods' => [
                    'SetHeader' => ['Generated On: ' . date("r")],
                    'SetFooter' => ['|Page {PAGENO}|'],
                ]
            ]);
            $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
            $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
            $dir = dirname($fullPath);
            if(!FileHelper::createDirectory($dir)) {
                return false;
            }
            $pdf->output($content, $fullPath, \kartik\mpdf\Pdf::DEST_FILE);

            return $this->redirect('index');
        }

        return $this->renderAjax('_form2', [
            'model' => $model,
            'clientModel' => $clientModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateCancel($id)
    {
        $model = $this->findModel($id);
        $clientModel = $this->findClientModel($model->client);

        $model->status = $model::STATUS_CANCEL;
        $model->save();

        return $this->redirect('index');

    }

    /**
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateDone($id)
    {
        $model = $this->findModel($id);
        $clientModel = $this->findClientModel($model->client);

        $model->status = $model::STATUS_DONE;
        $model->save();

        return $this->redirect('index');
    }

    /**
     * Deletes an existing Quotation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->active = 0;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionItem($id) {

        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();

        $params = array_merge(['quotation_id'=>$id], Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search($params);

        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        /*$query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);*/

        return $this->render('item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'quotationModel' => $quotationModel,
            'message'=>'',
            'error'=>false
        ]);
    }


    /**
     * List and select all Quotation models.
     * @return mixed
     */
    public function actionSelectDiscount($id)
    {
        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->post());
        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('select_discount', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List and select all Quotation models.
     * @return mixed
     */
    public function actionSelectDiscountRm($id)
    {
        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->post());
        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('select_discount', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List and select all Quotation models.
     * @return mixed
     */
    public function actionUpdateAutoCalc($id)
    {
        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->post());
        if(Yii::$app->request->post()) {
            $checked = Yii::$app->request->post('checked');

            $allQuotationItem = QuotationItem::updateAll(['auto_calc'=>0], 'quotation_id = :id', [':id' => $id]);
            
            if($checked != "") {
                foreach(explode(",", $checked) as $check) {
                    $quotationItem = QuotationItem::findOne($check);
                    $quotationItem->auto_calc = 1;
                    $quotationItem->save();
                }

            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('select_discount', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Product models by ajax.
     * @return mixed
     */
    public function actionProductAdd($id)
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $models = $dataProvider->getModels();
        $modelQuotation = Quotation::findOne($id);

        $currency = "";
        foreach($models as $model) {
            if($currency == "") {
                $currency = $model->project_currency;
            }
            if($currency != $model->project_currency) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'message'=>'Currency is not matched, Please choose same currency!',
                    'error'=>true
                ];
            }
            $countmodelQuotationItem = QuotationItem::find()
                ->where(['quotation_id' => $id, 'active'=>1])
                ->andWhere(['<>', 'project_currency', $model->project_currency])
                ->count();

            if($countmodelQuotationItem>0) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'message'=>'Currency is not matched, Please choose same currency!',
                    'error'=>true
                ];
            }
        }

        foreach($models as $model) {
            $modelQuotationItem = QuotationItem::findOne(['quotation_id' => $id,'product_id'=>$model->id, 'active'=>1]);

            if(empty($modelQuotationItem))
                $modelQuotationItem = new QuotationItem;

            $modelQuotationItem->quotation_id = $id;
            $modelQuotationItem->product_id = $model->id;
            $modelQuotationItem->attributes = $model->attributes;
            if(!$modelQuotationItem->save()) {
                print_r($modelQuotationItem->getErrors());die;
            }

        }

        return $this->renderAjax('item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'quotationModel' => $modelQuotation,
            'message'=>'',
            'error'=>false
        ]);
    }


    /**
     * Delete quotation product
     * @return mixed
     */
    public function actionItemDelete($id)
    {
        $modelQuotationItem = QuotationItem::findOne($id);
        $modelQuotation = Quotation::findOne($modelQuotationItem->quotation_id);
        if($modelQuotationItem === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        //$modelQuotationItem->delete();
        $modelQuotationItem->active = 0;
        $modelQuotationItem->save();
        
        /*$searchModey = new ProductSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $modelQuotationItem
        ]);

        return $this->renderAjax('item', [
            'quotationModel'=>$modelQuotation,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $searchModel = new QuotationItemSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'quotationModel' => $modelQuotation,
            'message'=>'',
            'error'=>''
        ]);
    }

    public function actionGenerate($id) {
        $model = $this->findModel($id);
        $authassignmentModel = $this->findAuthAssignmentModel($model->created_by);

        $query = QuotationItem::find()->where(['quotation_id'=>$id, 'active'=>1]);

        if(!Yii::$app->request->get('status') && Yii::$app->request->get('status') != 'bypass') {
            foreach($query->all() as $item) {
                if($item->discount > $item->threshold_discount) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $model->status = $model::STATUS_PENDING;
                    $model->save();

                    return ['success' => false, 'model'=>$model];

                }
            }
        }

        $model->status = $model::STATUS_APPROVE;
        if($model->save()) {
            $content = $this->renderPartial('document_pdf', [
                        'model' => $model,
                        'authassignmentModel'=>$authassignmentModel
                        ]);

            $pdf = new \kartik\mpdf\Pdf([
                'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
                'format' => \kartik\mpdf\Pdf::FORMAT_A4,
                'content' => $content,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                //'cssFile' => '@vendor/npm-asset/bootstrap/dist/css/bootstrap.css',
                'cssInline' => $this->defaultCss,
                'options' => [
                    'title' => $model->doc_no,
                    'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
                ],
                'methods' => [
                    'SetHeader' => ['Generated On: ' . date("r")],
                    'SetFooter' => ['|Page {PAGENO}|'],
                ]
            ]);
            $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
            $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
            $dir = dirname($fullPath);
            if(!FileHelper::createDirectory($dir)) {
                return false;
            }
            $pdf->output($content, $fullPath, \kartik\mpdf\Pdf::DEST_FILE);

        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true, 'model'=>$model];
        
    }

    /**
     * Displays a single Quotation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDocument($id)
    {
        $model = $this->findModel($id);
        $pdf = new \kartik\mpdf\Pdf();
        $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
        $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
        $mpdf = $pdf->api; // fetches mpdf api
        $pagecount = $mpdf->setSourceFile($fullPath);
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        return $mpdf->Output();

        return $this->render('document', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGeneratePdf($id, $format="pdf") {
        $model = $this->findModel($id);
        $authassignmentModel = $this->findAuthAssignmentModel($model->created_by);
        $content = $this->renderPartial('document_pdf', [
                     'model' => $model,
                     'authassignmentModel'=>$authassignmentModel
                     ]);

        if($format==="raw") {
            // Start buffering
            ob_start();

            // Output stuff (probably not this simple, might be custom CMS functions...
            echo "<style>";
            echo $this->defaultCss;
            echo "</style>";

            // Get value of buffering so far
            $getContent = ob_get_contents();

            // Stop buffering
            ob_end_clean();

            return $content.$getContent;
        }

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            //'cssFile' => '@vendor/npm-asset/bootstrap/dist/css/bootstrap.css',
            'cssInline' => $this->defaultCss,
            'options' => [
                'title' => $model->doc_no,
                'subject' => 'Generating PDF files via yii2-mpdf extension has never been easy'
            ],
            'methods' => [
                'SetHeader' => ['Generated On: ' . date("r")],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
        $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
        //return $pdf->output($content, $fullPath, \kartik\mpdf\Pdf::DEST_FILE);
        return $pdf->render();
    }

    /**
     * List and select all Quotation models.
     * @return mixed
     */
    public function actionQuotation()
    {
        $searchModel = new QuotationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $dataProvider->setPagination(['pageSize' => 10]);

        return $this->renderAjax('select', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Quotation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quotation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Quotation::findOne($id);

        if(!Yii::$app->user->can('view-quotation') && Yii::$app->user->can('view-quotation-byownself')) {
            $model = Quotation::findOne(['id' => $id, 'created_by'=>Yii::$app->user->id]);
        }         
        
        if ($model !== null ) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findClientModel($id)
    {
        /*if (($model = Client::findOne($id)) !== null) {
            return $model;
        }*/
        return Client::findOne($id);

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProductModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAuthAssignmentModel($id)
    {
        if (($model = AuthAssignment::findOne(['user_id'=>$id])) !== null) {
            $model->role = $model->item_name;
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
