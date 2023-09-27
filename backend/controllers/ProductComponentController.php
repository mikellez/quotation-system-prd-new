<?php

namespace backend\controllers;

use Yii;
use common\models\ProductComponent;
use common\models\search\ProductComponentSearch;
use common\models\TmpProductComponent;
use common\models\search\TmpProductComponentSearch;
use common\models\TmpProducts;
use common\models\search\TmpProductsSearch;
use common\models\Product;
use common\models\search\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductComponentController implements the CRUD actions for ProductComponent model.
 */
class ProductComponentController extends Controller
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
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProductComponent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductComponentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductComponent model.
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
     * Creates a new ProductComponent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductComponent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductComponent model.
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
     * Deletes an existing ProductComponent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['/product/update', 'id'=>$model->products_id]);
    }

    /**
     * Deletes an existing ProductComponent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTmpDelete($id)
    {
        $modelTmpProductComponent = TmpProductComponent::findOne($id);
        $modelTmpProductComponent->delete();

        $session = Yii::$app->session;
        $model = TmpProducts::findOne($session['TmpProducts']['id']);

        $searchModel = new TmpProductComponentSearch();
        $dataProvider = $searchModel->search([]);
        $dataProvider->query->andWhere(['products_id'=>$model->id]);
        $model->product_type = 'service_package';
        $model->scenario = TmpProducts::SCENARIO_CREATE;

        return $this->renderAjax('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'message'=>'',
            'error'=>false
        ]);

    }

    /**
     * List and select all Product models.
     * @return mixed
     */
    public function actionSelect()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->setPagination(['pageSize' => 10]);
        $dataProvider->query->andWhere(['<>', 'status', 0]);
        $dataProvider->query->andWhere(['=', 'product_type', 'normal']);

        return $this->renderAjax('select', [
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
        $productSearchModel = new ProductSearch();
        $dataProviderProduct = $productSearchModel->search(Yii::$app->request->post());
        $models = $dataProviderProduct->getModels();

        $session = Yii::$app->session;

        foreach($models as $model) {
            if($id == 0) {
                $modelProductComponent = TmpProductComponent::findOne(['products_id'=>$session['TmpProducts']['id'], 'product_component_id'=>$model->id]);
                if(empty($modelProductComponent))
                    $modelProductComponent = new TmpProductComponent;
                
                $modelProductComponent->products_id = $session['TmpProducts']['id'];

            } else {

                $modelProductComponent = ProductComponent::findOne(['products_id'=>$id, 'product_component_id'=>$model->id]);
                if(empty($modelProductComponent))
                    $modelProductComponent = new ProductComponent;

                $modelProductComponent->products_id = $id;
            }


            $modelProductComponent->product_component_id = $model->id;
            if(!$modelProductComponent->save()) {
                print_r($modelProductComponent->getErrors());die;
            }


        }

        if($id == 0) {
            
            $searchModel = new TmpProductComponentSearch();
            $dataProvider = $searchModel->search([]);
            $dataProvider->query->andWhere(['products_id'=>$session['TmpProducts']['id']]);
            $productModel = TmpProducts::findOne($session['TmpProducts']['id']);
            $productModel->product_type = Product::SERVICE_PACKAGE;
            $productModel->scenario = TmpProducts::SCENARIO_CREATE;
        } else {
            $searchModel = new ProductComponentSearch();
            $dataProvider = $searchModel->search([]);
            $dataProvider->query->andWhere(['products_id'=>$id]);
            $productModel = Product::findOne($id);
            $productModel->product_type = Product::SERVICE_PACKAGE;

        }


        return $this->renderAjax('index', [
            'model' => $productModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'message'=>'',
            'error'=>false
        ]);
    }



    /**
     * Finds the ProductComponent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductComponent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductComponent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
