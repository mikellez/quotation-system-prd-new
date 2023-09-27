<?php

namespace backend\controllers;

use Yii;
use common\models\Quotation;
use common\models\search\QuotationSearch;
use common\models\ProductComponent;
use common\models\search\ProductComponentSearch;
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
use common\models\LoginForm;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\data\Pagination;

/**
 * QuotationController implements the CRUD actions for Quotation model.
 */
class QuotationController extends Controller
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
        $dataProviderAll->query->andWhere(['=', 'master', 0]);
        //$dataProvider->query->andWhere(['<>', 'slave', 1]);
        $dataProviderAll->query->andWhere(['=', 'quotation.active', 1]);
        //$dataProviderAll->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderAll->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderApprove = $searchModelDraft->search(Yii::$app->request->queryParams);
        $dataProviderApprove->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderApprove->setPagination(['pageSize' => 10]);
        $dataProviderApprove->query->andWhere(['=', 'master', 0]);
        $dataProviderApprove->query->andWhere(['=', 'active', 1]);
        //$dataProviderApprove->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderApprove->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderDraft = $searchModelDraft->search(Yii::$app->request->queryParams);
        $dataProviderDraft->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderDraft->setPagination(['pageSize' => 10]);
        $dataProviderDraft->query->andWhere(['=', 'master', 0]);
        $dataProviderDraft->query->andWhere(['=', 'active', 1]);
        //$dataProviderDraft->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderDraft->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderPending = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderPending->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderPending->setPagination(['pageSize' => 10]);
        $dataProviderPending->query->andWhere(['=', 'master', 0]);
        $dataProviderPending->query->andWhere(['=', 'active', 1]);
        //$dataProviderPending->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderPending->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderConfirm = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderConfirm->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderConfirm->setPagination(['pageSize' => 10]);
        $dataProviderConfirm->query->andWhere(['=', 'master', 0]);
        $dataProviderConfirm->query->andWhere(['=', 'active', 1]);
        //$dataProviderConfirm->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderConfirm->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderDone = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderDone->sort->defaultOrder = ['created_at' => SORT_DESC];
        $dataProviderDone->setPagination(['pageSize' => 10]);
        $dataProviderDone->query->andWhere(['=', 'master', 0]);
        $dataProviderDone->query->andWhere(['=', 'active', 1]);
        //$dataProviderDone->query->andWhere(['is', 'quotation.quotation_id', new \yii\db\Expression('NULL')]);
        $dataProviderDone->query->andWhere(['=', 'doc_type', 'quotation']);

        $dataProviderApprove->query->andWhere(['=', 'quotation.status', Quotation::STATUS_APPROVE]);
        $dataProviderDraft->query->andWhere(['=', 'quotation.status', Quotation::STATUS_DRAFT]);
        $dataProviderPending->query->andWhere(['=', 'quotation.status', Quotation::STATUS_PENDING]);
        $dataProviderConfirm->query->andWhere(['=', 'quotation.status', Quotation::STATUS_CONFIRM]);
        $dataProviderDone->query->andWhere(['=', 'quotation.status', Quotation::STATUS_DONE]);
        $dataProviderDone->query->andWhere(['=', 'doc_type', 'quotation']);

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
        $request = Yii::$app->request;
        $params = [];
        if($request->get('type')) {
            $params['type'] = $request->get('type');
            $model->doc_name = $request->get('type') ? 'Group' : '';
        }
        if($request->get('selectedQuotation')) {
            $params['selectedQuotation'] = $request->get('selectedQuotation');
        }

        if ($model->load($request->post())) {
            //$model->client = Yii::$app->request->post('Client')['company'];
            $model->status = $model::STATUS_DRAFT;
            $model->doc_type = 'quotation';
            $model->doc_type2 = $request->get('type') ? $request->get('type') : null;
            $model->save();

            $params['id'] = $model->id;

            if($request->get('type') == 'combine') {
                foreach(explode(',', $request->get('selectedQuotation')) as $child_id) {
                    $modelQuotationChild = Quotation::findOne($child_id);
                    $modelQuotationChild->quotation_id = $model->id;
                    $modelQuotationChild->doc_type2 = $request->get('type');
                    $modelQuotationChild->active = 1;
                    $modelQuotationChild->save();

                    $modelQuotationItem = QuotationItem::findOne(['quotation_id' => $model->id,'link_quotation_id'=>$child_id]);
                    if(empty($modelQuotationItem)) {
                        $modelQuotationItem = new QuotationItem;
                        $modelQuotationItem->link_quotation_id = $child_id;
                        $modelQuotationItem->quotation_id = $model->id;
                    }

                    $modelQuotationItem->quantity = 1;
                    $modelQuotationItem->retail_base_price = $modelQuotationChild->total_price_after_disc;
                    $modelQuotationItem->name = $modelQuotationChild->doc_name;
                    $modelQuotationItem->description = $modelQuotationChild->doc_name;
                    $modelQuotationItem->attributes = $modelQuotationChild->attributes;
                    $modelQuotationItem->save();

                }

                return $this->redirect(['combine-item', 'id'=>$model->id]);
            }
            
            return $this->redirect(['item', 'id'=>$model->id]);
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
        $newModel->doc_type = "quotation";
        $newModel->status = $model::STATUS_DRAFT;
        $newModel->rev_no = 1;
        $newModel->save();

        $modelItems = $model->item;
        foreach($modelItems as $modelItem) {
		if($modelItem->product_parent_id == 0) {
		    $newmodelItem = new QuotationItem;
		    $newmodelItem->attributes = $modelItem->attributes;
		    $newmodelItem->product_type = $modelItem->product_type;
		    $newmodelItem->quotation_id = $newModel->id;
		    if($newmodelItem->save()) {
				$productComponentSearchModel = new ProductComponentSearch();
				$dataProviderProductComponent = $productComponentSearchModel->search([]);
				$dataProviderProductComponent->query->andWhere(['products_id'=>$newmodelItem->product_id]);
				foreach($dataProviderProductComponent->models as $m) {
				    $modelQuotationItem2 = new QuotationItem;
				    $modelQuotationItem2->attributes = $m->productComponent->attributes;
				    $modelQuotationItem2->quotation_id = $newModel->id;
				    $modelQuotationItem2->product_id = $m->productComponent->id;
				    $modelQuotationItem2->product_parent_id = $newmodelItem->id;
				    if(!$modelQuotationItem2->save()) {
					print_r($modelQuotationItem2->getErrors());die;
				    }
				}

			}
		}

        }

        return $this->redirect(['view', 'id' => $newModel->id]);
    }

    /**
     * Creates a new revision of Quotation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($id)
    {
        $model = Quotation::findOne($id);
        $newModel = new Quotation;
        $newModel->attributes = $model->attributes;
        $newModel->doc_no = "";
        $newModel->doc_type = "quotation";
        $newModel->status = $model::STATUS_DRAFT;
        if(!$newModel->save()) {
            return print_r($newModel->getErrors());
        }

        $modelItems = $model->item;
        foreach($modelItems as $modelItem) {
		if($modelItem->product_parent_id == 0) {
		    $newmodelItem = new QuotationItem;
		    $newmodelItem->attributes = $modelItem->attributes;
		    $newmodelItem->product_type = $modelItem->product_type;
		    $newmodelItem->quotation_id = $newModel->id;
		    if($newmodelItem->save()) {
				$productComponentSearchModel = new ProductComponentSearch();
				$dataProviderProductComponent = $productComponentSearchModel->search([]);
				$dataProviderProductComponent->query->andWhere(['products_id'=>$newmodelItem->product_id]);
				foreach($dataProviderProductComponent->models as $m) {
				    $modelQuotationItem2 = new QuotationItem;
				    $modelQuotationItem2->attributes = $m->productComponent->attributes;
				    $modelQuotationItem2->quotation_id = $newModel->id;
				    $modelQuotationItem2->product_id = $m->productComponent->id;
				    $modelQuotationItem2->product_parent_id = $newmodelItem->id;
				    if(!$modelQuotationItem2->save()) {
					print_r($modelQuotationItem2->getErrors());die;
				    }
				}

			}
		}

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
        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->doc_type2 == 'combine') {
                return $this->redirect(['combine-item', 'id' => $model->id]);
            }
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
        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();
        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->query->andWhere(['=', 'product_parent_id', 0]);

        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $attribute = Yii::$app->request->post('editableAttribute');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($attribute=='discount'){
                $post['QuotationItem']['discountrm'] = $quotationItem->retail_base_price * $posted['discount'] * 0.01;
            }
            if($attribute=='discountrm'){
                $post['QuotationItem']['discount'] = (1 - (($quotationItem->retail_base_price - $posted['discountrm'])/$quotationItem->retail_base_price)) * 100;
            }
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
            'quotationModel' => $quotationModel,
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
        $dataProvider->query->andWhere(['=', 'product_parent_id', 0]);

	// create the pagination object
	$pagination = new Pagination([
	    'totalCount' => $dataProvider->getTotalCount(),
	    'pageSize' => 10,
		'params' => array_merge(Yii::$app->request->get(), ['id' => $_GET['id']]),
	]);

	// pass the pagination object to the data provider
	$dataProvider->pagination = $pagination;

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

    public function actionCombineItem($id) {

        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationItemSearch();

        if(Yii::$app->request->post() && Yii::$app->request->post('hasEditable')) {
            $quotationItemId = Yii::$app->request->post('editableKey');
            $quotationItem = QuotationItem::findOne($quotationItemId);
            $quotation = Quotation::findOne($quotationItem->link_quotation_id);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $quotation->doc_name = $_POST['QuotationItem'][0]['doc_name'];
            if($quotation->save()) {
                return $out;
            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('combine-item', [
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
    public function actionSelect($id)
    {
        $quotationModel = $this->findModel($id);
        $searchModel = new QuotationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

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

        $dataProvider->query->andWhere(['=', 'doc_type', 'quotation']);
        $dataProvider->query->andWhere(['=', 'active', '1']);
        $dataProvider->query->andWhere(['IS', 'doc_type2', new \yii\db\Expression('NULL')]);
        $dataProvider->query->andWhere(['IS', 'quotation_id', new \yii\db\Expression('NULL')]);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $this->renderAjax('select', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	        'quotationModel' => $quotationModel
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
            $attribute = Yii::$app->request->post('editableAttribute');
            $quotationItem = QuotationItem::findOne($quotationItemId);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post = [];
            $posted = current($_POST['QuotationItem']);
            $post['QuotationItem'] = $posted;
            if($attribute=='discount'){
                $post['QuotationItem']['discountrm'] = $quotationItem->retail_base_price * $posted['discount'] * 0.01;
            }
            if($attribute=='discountrm'){
                $post['QuotationItem']['discount'] = (1 - (($quotationItem->retail_base_price - $posted['discountrm'])/$quotationItem->retail_base_price)) * 100;
            }

            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'product_parent_id' => 0, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('select_discount', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	    'quotationModel' => $quotationModel
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
	    if(Yii::$app->request->post('editableAttribute')=='discountrm' && $posted['discountrm'] >= $quotationItem->retail_base_price){
       	    	$out = Json::encode(['output'=>'', 'message'=>'Discount cannot more than unit price!']);
		return $out;
	    }
            if($quotationItem->load($post) && $quotationItem->save()) {
                return $out;
            }
        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1, 'product_parent_id'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('select_discount', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	    'quotationModel' => $quotationModel
        ]);
    }

    public function actionQuotationAdd($id)
    {
        $searchModel = new QuotationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $models = $dataProvider->getModels();
        $modelQuotation = Quotation::findOne($id);

        foreach($models as $model) {
            $model->quotation_id = $modelQuotation->id;
            $model->doc_type2 = 'combine';
            $model->active = 1;
            $model->save();

            $modelQuotationItem = QuotationItem::findOne(['quotation_id' => $id,'link_quotation_id'=>$model->id]);
            if(empty($modelQuotationItem)) {
                $modelQuotationItem = new QuotationItem;
                $modelQuotationItem->link_quotation_id = $model->id;
                $modelQuotationItem->quotation_id = $model->id;
            }

            $modelQuotationItem->quantity = 1;
            $modelQuotationItem->retail_base_price = $model->total_price_after_disc;
            $modelQuotationItem->name = $model->doc_name;
            $modelQuotationItem->description = $model->doc_name;
            $modelQuotationItem->attributes = $model->attributes;
            $modelQuotationItem->save();

        }

        $query = QuotationItem::find()->where(['quotation_id' => $id, 'active'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('combine-item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'quotationModel' => $modelQuotation,
            'message'=>'',
            'error'=>false
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
            $modelQuotationItem->product_parent_id = 0;
            $modelQuotationItem->product_type = $model->product_type;
            $modelQuotationItem->attributes = $model->attributes;
            $modelQuotationItem->quantity = 1;
            if($modelQuotationItem->save()) {
		if($modelQuotationItem->product_type == 'service_package') {
			$productComponentSearchModel = new ProductComponentSearch();
			$dataProviderProductComponent = $productComponentSearchModel->search([]);
			$dataProviderProductComponent->query->andWhere(['products_id'=>$model->id]);
			foreach($dataProviderProductComponent->models as $m) {
			    $modelQuotationItem2 = new QuotationItem;
			    $modelQuotationItem2->attributes = $m->productComponent->attributes;
			    $modelQuotationItem2->quotation_id = $id;
			    $modelQuotationItem2->product_id = $m->productComponent->id;
			    $modelQuotationItem2->product_parent_id = $modelQuotationItem->id;
			    if(!$modelQuotationItem2->save()) {
				print_r($modelQuotationItem2->getErrors());die;
			    }
			}
		}
            } else {
                print_r($modelQuotationItem->getErrors());die;
            }

        }

        /*return $this->renderAjax('item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'quotationModel' => $modelQuotation,
            'message'=>'',
            'error'=>false
        ]);*/
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
        if($modelQuotationItem->save()) {
            $modelLinkQuotation = Quotation::findOne($modelQuotationItem->linkQuotation->id);
            if($modelLinkQuotation->doc_type2 == 'combine') {
                $modelLinkQuotation->doc_type2 = NULL;
                $modelLinkQuotation->quotation_id = NULL;
                $modelLinkQuotation->save();
            } else {
                QuotationItem::updateAll(['active' => 0], ['=', 'product_parent_id', $id]);
            }
        }
        
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

        $query = QuotationItem::find()->where(['quotation_id'=>$id, 'product_parent_id'=>0, 'active'=>1]);
	
	if($model->doc_type2 == 'combine') {
	} else {
		if(!Yii::$app->request->get('status') && Yii::$app->request->get('status') != 'bypass') {
		    foreach($query->all() as $item) {
			$discount2 = $item->discountrm > 0 ? $item->discountrm * $item->quantity : 0;
			if($item->discount > $item->threshold_discount || $item->discountrm/$item->retail_base_price*100 > $item->threshold_discount) {
			    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			    $model->status = $model::STATUS_PENDING;
			    $model->save();

			    return ['success' => false, 'model'=>$model];

			}
		    }
		}
	}

        $model->status = $model::STATUS_APPROVE;
        $model->approved_by = Yii::$app->user->identity->id;
        $model->approved_at = time();

        if($model->save()) {
            $content = $this->renderPartial('document_pdf', [
                        'model' => $model,
                        'authassignmentModel'=>$authassignmentModel
                        ]);

            $header = $this->renderPartial('document_pdf_header', [
                'model' => $model,
                'authassignmentModel'=>$authassignmentModel
            ]);

            $footer = $this->renderPartial('document_pdf_footer', [
                'model' => $model,
                'authassignmentModel'=>$authassignmentModel
            ]);

            /*$pdf = new \kartik\mpdf\Pdf([
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
                //'orientation' => \kartik\mpdf\Pdf::ORIENT_LANDSCAPE,
                'marginTop' => 0,
                'marginBottom' => 0,
                //'marginLeft' => 0,
                //'marginRight' => 0,
                //'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
               'methods' => [
                    //'SetHeader' => ['Generated On: ' . date("r")],
                    //'SetFooter' => ['|Page {PAGENO}|'],
                    'SetHeader' => $header,
                    'SetFooter' => $footer
                ]
            ]);
            $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
            $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
            $dir = dirname($fullPath);
            if(!FileHelper::createDirectory($dir)) {
                return false;
            }
            $pdf->output($content, $fullPath, \kartik\mpdf\Pdf::DEST_FILE);
            */
            $mpdf = new \Mpdf\Mpdf(['default_font'=>'helvetica']);

            $mpdf->WriteHTML($header);
            $mpdf->WriteHTML($content);
            //$mpdf->SetHTMLFooter($footer);
            //$mpdf->WriteHTML('<pagebreak page-selector="letterhead" />');
            //$mpdf->WriteHTML($letter);
            //$mpdf->WriteHTML('<pagebreak page-selector="letterhead" />');
            //$mpdf->WriteHTML($letter);
            $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
            $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
            $dir = dirname($fullPath);
            if(!FileHelper::createDirectory($dir)) {
                return false;
            }

            $mpdf->Output($fullPath, 'F');

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
        $pdfFile = Yii::getAlias('/quotations/'.$model->doc_no.'.pdf');
        $fullPath = Yii::getAlias('@backend/web/storage'.$pdfFile);
        /*$pdf = new \kartik\mpdf\Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        $pagecount = $mpdf->setSourceFile($fullPath);
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        return $mpdf->Output();*/
        return Yii::$app->response->sendFile($fullPath, $model->doc_no.'.pdf', ['inline'=>true]);

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
        $header = $this->renderPartial('document_pdf_header', [
            'model' => $model,
            'authassignmentModel'=>$authassignmentModel
        ]);
        $footer = $this->renderPartial('document_pdf_footer', [
            'model' => $model,
            'authassignmentModel'=>$authassignmentModel
        ]);

        if($format==="raw") {
            $mpdf = new \Mpdf\Mpdf(['default_font'=>'helvetica']);
            $mpdf->SetWatermarkText("Only for preview");
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($header);
            $mpdf->WriteHTML($content);
            //$mpdf->SetHTMLFooter($footer);
            $mpdf->Output();
        }

        if($format==="raw2") {
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

        /*$pdf = new \kartik\mpdf\Pdf([
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
        return $pdf->render();*/
        

    }

    public function actionTestPdf($id) {
        $model = $this->findModel($id);
        $authassignmentModel = $this->findAuthAssignmentModel($model->created_by);
        $content = $this->renderPartial('document_pdf', [
                    'model' => $model,
                    'authassignmentModel'=>$authassignmentModel
                    ]);

        $header = $this->renderPartial('document_pdf_header', [
            'model' => $model,
            'authassignmentModel'=>$authassignmentModel
        ]);

        $footer = $this->renderPartial('document_pdf_footer', [
            'model' => $model,
            'authassignmentModel'=>$authassignmentModel
        ]);

        $mpdf = new \Mpdf\Mpdf(['default_font'=>'helvetica']);

        $mpdf->WriteHTML($header);
        $mpdf->WriteHTML($content);
        //$mpdf->SetHTMLFooter($footer);

        $mpdf->Output();
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

    public function actionSignTransaction() {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return Json::encode(['success'=>true, 'message'=>'Success!']);
        } else {
            $model->password = '';

            return Json::encode(['success'=>false,'message'=>'Incorrect password!']);
        }

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

    public function actionTriggerQuotationItemSave() {
        $quotation = Quotation::find()
            ->where(['active'=>1])
            ->limit(50,100)
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        foreach($quotation as $item) {
            $quoItem = QuotationItem::find()
                ->where(['active'=>1,'product_parent_id'=>0,'quotation_id'=>$item->id])
                ->all();	
            foreach($quoItem as $item2) {
                $item2->save();
            }
        }
    }
}
