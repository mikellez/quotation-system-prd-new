<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\search\ProductSearch;
use common\models\ProductComponent;
use common\models\search\ProductComponentSearch;
use common\models\TmpProducts;
use common\models\search\TmpProductsSearch;
use common\models\TmpProductComponent;
use common\models\search\TmpProductComponentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;

use yii\helpers\Json;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['select'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['import'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['duplicate'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete-all'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['reorder-up'],
                        'roles' => ['create-product'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['reorder-down'],
                        'roles' => ['create-product'],
                    ],
                ]
            ]
        ];
    }
    
    public function actionDuplicate($id) {
        
        $model = Product::findOne($id);
        $newModel = new Product;
        
        $newModel->attributes = $model->attributes;
        if($newModel->save()) {
            $modelComponents = ProductComponent::findAll(['products_id' => $id]);

            foreach ($modelComponents as $modelComponent) {
                $newModelComponent = new ProductComponent;
                $newModelComponent->attributes = $modelComponent->attributes;
                $newModelComponent->products_id = $newModel->id;
                if(!$newModelComponent->save()) {
                    print_r($modelComponent->getError());die;
                }
                
            }

        }
        
        return Json::encode(['error'=>false, 'msg'=>'']);
        
    }
    
    public function actionDeleteAll() {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        $models = $dataProvider->getModels();
        
        foreach($models as $model) {
            $model->delete();
            
        }
        
        return Json::encode(['error'=>false, 'msg'=>'']);
    }
    
    public function actionReorderUp($id)
    {
        $model = Product::findOne($id);

        if ($model) {
            $previousModel = Product::find()
                ->where(['<', 'sequence', $model->sequence])
                ->orderBy(['sequence' => SORT_DESC])
                ->one();

            if ($previousModel) {
                // Swap the sequence values
                list($model->sequence, $previousModel->sequence) = [$previousModel->sequence, $model->sequence];

                // Save the changes
                $model->save();
                $previousModel->save();
            }
        }

        return Json::encode(['error'=>false, 'msg'=>'']);
    }
    
    public function actionReorderDown($id)
    {
        $model = Product::findOne($id);

        if ($model) {
            $nextModel = Product::find()
                ->where(['>', 'sequence', $model->sequence])
                ->orderBy(['sequence' => SORT_ASC])
                ->one();

            if ($nextModel) {
                // Swap the sequence values
                list($model->sequence, $nextModel->sequence) = [$nextModel->sequence, $model->sequence];

                // Save the changes
                $model->save();
                $nextModel->save();
            }
        }

        return Json::encode(['error'=>false, 'msg'=>'']);
    }


    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 10]);
        $dataProvider->query->orderBy(['sequence'=>SORT_ASC]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        $model = new TmpProducts();
        /*if($session['TmpProducts']) {
            $model = TmpProducts::findOne($session['TmpProducts']['id']);
        }*/
        $model->scenario = TmpProducts::SCENARIO_CREATE;
        $productModel = new Product();
        $searchProductComponentModel = new TmpProductComponentSearch();
        $dataProviderProductComponent = $searchProductComponentModel->search([]);
        $dataProviderProductComponent->query->andWhere(['products_id'=>0]);

        if ($model->load(Yii::$app->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            $model->scenario = TmpProducts::SCENARIO_UPDATE;
            if($model->save()) {
                
                
                $productModel->attributes = $model->attributes;
                if($productModel->save()) {
                    $productModel->sequence = $model->id;
                    $productModel->update();
                    
                    if($session['TmpProducts']) {
                        $modelTmpProducts = TmpProducts::findOne($session['TmpProducts']['id']);
                        $searchProductComponentModel = new TmpProductComponentSearch();
                        $dataProviderProductComponent = $searchProductComponentModel->search([]);
                        $dataProviderProductComponent->query->andWhere(['products_id'=>$modelTmpProducts->id]);
                    }
                    
                    foreach($dataProviderProductComponent->getModels() as $tmpProductComponentModel) {
                        $productComponentModel = new ProductComponent();
                        $productComponentModel->attributes = $tmpProductComponentModel->attributes; 
                        $productComponentModel->products_id = $productModel->id;
                        $productComponentModel->save();
                    }
                }

                $session = Yii::$app->session;
                $session->remove('TmpProducts');

                return $this->redirect(['view', 'id' => $productModel->id]);
            } 

        } else {
            //if(!$session['TmpProducts']) {
                if($model->save()) {
                    
                    $session['TmpProducts'] = [ 'id' => $model->id ];
                }
            //}
        }

        //$searchProductComponentModel = new TmpProductComponentSearch();
        //$dataProviderProductComponent = $searchProductComponentModel->search([]);
        //$dataProviderProductComponent->query->andWhere(['=','products_id', $model->id]);

        return $this->render('create', [
            'model' => $model,
            'searchProductComponentModel' => $searchProductComponentModel,
            'dataProviderProductComponent' => $dataProviderProductComponent
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchProductComponentModel = new ProductComponentSearch();
        $dataProviderProductComponent = $searchProductComponentModel->search([]);
        $dataProviderProductComponent->query->andWhere(['products_id'=>$id]);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if($model->save()) return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchProductComponentModel' => $searchProductComponentModel,
            'dataProviderProductComponent' => $dataProviderProductComponent
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id){
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

        return $this->renderAjax('select', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Import a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImport()
    {
        $model = new Product();
        //$model->scenario = Product::SCENARIO_IMPORT;

        if ($model->load(Yii::$app->request->post())) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');

            if($model->importFile != null) {
                $filename = 'IMPORT-PRODUCTS';
                //$path = Yii::getAlias('@webroot/../storage/'.$filename.'.'.$model->importFile->extension);
                $path = Yii::getAlias('@backend/web/storage/'.$filename.'.'.$model->importFile->extension);
                $uploaded = $model->importFile->saveAs($path);
                if($uploaded) {
                    //identify filename
                   $readerType = IOFactory::identify($path);
                   //read filename based on file type
                   $objReader = IOFactory::createReader($readerType);
                   //load filename
                   $objPHPExcel = $objReader->load($path);
                   //read sheet 1 Array 1
                    $sheet = $objPHPExcel->getSheet(0);
                    $highestDataRow = $sheet->getHighestDataRow();
                    $highestDataColumn = $sheet->getHighestDataColumn();

                    $fileHeader = [];
                    $fileContent = [];
                    //get content
                    for($row = 1; $row <= $highestDataRow; $row++) {
                        $rowData = $sheet->rangeToArray('A'. $row . ':'. $highestDataColumn . $row, NULL, TRUE, FALSE);
                        if($row==1) {
                            //$fileHeader = [$row][$highestDataColumn];
                            if(!in_array('col_action',$rowData[0])) {
                                echo 'File wrong format'; die;
                            }

                            if(!in_array('id',$rowData[0])) {
                                echo 'File wrong format'; die;
                            }

                            $fileHeader = $rowData[0];
                            continue;
                        }

                        foreach($rowData[0] as $key=>$item) {
                            $tempContent[$fileHeader[$key]] = $item;
                        }

                        $fileContent[] = $tempContent;

                    }

                    foreach($fileContent as $content) {

                        $productModel = Product::findOne($content['id']);
                        $clonedContent = $content;

                        unset($clonedContent['col_action']);
                        if(isset($clonedContent['type'])) {
                            $clonedContent['type'] = strval($clonedContent['type']);
                        }

                        switch($content['col_action']) {
                            case 'INSERT':
                                if(empty($clonedContent['id'])) {
                                    unset($clonedContent['id']);
                                }

                                $productModel = new Product;
                                $productModel->attributes = $clonedContent;
                                if(!$productModel->save()) {
                                    print_r($productModel->getErrors());die;
                                }
                                break;

                            case 'UPDATE':
                                $productModel->attributes = $clonedContent;
                                $productModel->save();
                                break;

                            case 'DELETE':
                                $productModel->delete();
                                break;
                        }

                    }
                }
                Yii::$app->session->setFlash('success', 'Import file successfully!');
            }
            if($model->imageFile != null) {
                foreach ($model->imageFile as $file) {
                    $imagePath =  Yii::getAlias('/products/'.$file);

                    $fullPath = Yii::getAlias('@backend/web/storage'.$imagePath);
                    $dir = dirname($fullPath);
                    if(!FileHelper::createDirectory($dir) | !$file->saveAs($fullPath)) {
                    }
                }
                Yii::$app->session->setFlash('success', 'Import image successfully!');
            }
            //return $this->redirect('index');
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Export a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionExport()
    {
        $model = new Product();
        $model->scenario = Product::SCENARIO_EXPORT;
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
