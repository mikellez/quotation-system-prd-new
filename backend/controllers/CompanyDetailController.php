<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\CompanyDetail;
use common\models\search\CompanyDetailSearch;
use common\models\Company;
use common\models\search\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyDetailController implements the CRUD actions for CompanyDetail model.
 */
class CompanyDetailController extends Controller
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
     * Lists all CompanyDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CompanyDetail model.
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
     * Creates a new CompanyDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new CompanyDetail();
        $modelCompany = Company::findOne($id);

        $model->company = $modelCompany->id;
        $model->companyName = $modelCompany->company;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/company/update', 'id' => $model->company]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CompanyDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelCompany = Company::findOne($model->company);
        $model->companyName = $modelCompany->company;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/company/update', 'id' => $model->company]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CompanyDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['/company/update', 'id' => $model->company]);
    }

    /**
     * Finds the CompanyDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCompany($id) {
        return ArrayHelper::map(CompanyDetail::find()->where(['company'=>$id])->asArray()->all(), 'payment_tnc', 'payment_tnc');
    }
}
