<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\search\CompanySearch;
use common\models\CompanyDetail;
use common\models\search\CompanyDetailSearch;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchCompanyDetailModel = new CompanyDetailSearch();
        $dataProviderCompanyDetail = $searchCompanyDetailModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchCompanyDetailModel' => $searchCompanyDetailModel,
            'dataProviderCompanyDetail' => $dataProviderCompanyDetail,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        $searchCompanyDetailModel = new CompanyDetailSearch();
        $dataProviderCompanyDetail = $searchCompanyDetailModel->search(Yii::$app->request->queryParams);
        $dataProviderCompanyDetail->query->andWhere(['=', 'id', 0]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'searchCompanyDetailModel' => $searchCompanyDetailModel,
            'dataProviderCompanyDetail' => $dataProviderCompanyDetail,
            'disableDetail'=>true
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        $searchCompanyDetailModel = new CompanyDetailSearch();
        $dataProviderCompanyDetail = $searchCompanyDetailModel->search(Yii::$app->request->queryParams);
        $dataProviderCompanyDetail->query->andWhere(['=', 'company', $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchCompanyDetailModel' => $searchCompanyDetailModel,
            'dataProviderCompanyDetail' => $dataProviderCompanyDetail,
            'disableDetail'=>false
        ]);
    }

    /**
     * Deletes an existing Company model.
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Import a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImport()
    {
        $model = new Company();
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

                        $productModel = Company::findOne($content['id']);
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

                                $productModel = new Company;
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
                    $imagePath =  Yii::getAlias('/letterheads/'.$file);

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
        $model = new Company();
        $model->scenario = Company::SCENARIO_EXPORT;
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
