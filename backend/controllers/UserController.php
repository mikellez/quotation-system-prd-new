<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\search\UserSearch;
use common\models\Point;
use common\models\search\PointSearch;
use common\models\AuthAssignment;
use common\models\search\AuthAssignmentSearch;
use common\models\Company;
use common\models\search\CompanySearch;
use common\models\LoginForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 9;

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
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['authentication'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-password'],
                        'roles' => ['create-user-admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assign-password'],
                        'roles' => ['create-user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assign-password-by-user'],
                        'roles' => ['create-user'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 10]);
        $dataProvider->query->andWhere(['>', 'status', 0]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'authAssignmentModel' => $this->findAuthAssignmentModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $companyModel = new Company();
        $authAssignmentModel = new AuthAssignment();

        if (Yii::$app->request->post()) {

            $user = Yii::$app->request->post('User');
            $assignment = Yii::$app->request->post('AuthAssignment');
            
            $transaction = Yii::$app->db->beginTransaction();

            $model->username = $user['username'];
            $model->email = $user['email'];
            $model->password = $user['password'];
            $model->setPassword($user['password']);
            $model->generateAuthKey();
            $model->status = self::STATUS_ACTIVE;
            $model->company = $user['company'];
            $model->phoneno = $user['phoneno'];
            $model->code = $user['code'];
            $model->name = $user['name'];
            $model->signatureImageFile = UploadedFile::getInstance($model, 'signatureImageFile');

            if(!$model->save()) {
                $transaction->rollBack();
                return $this->render('create', [
                    'model' => $model,
                    'companyModel' => $companyModel,
                    'authAssignmentModel' => $authAssignmentModel
                ]);
            }

            $authAssignmentModel->user_id = strval($model->id);
            $authAssignmentModel->item_name = $assignment['role'];
            $authAssignmentModel->role = $assignment['role'];

            if(!$authAssignmentModel->save()) {
                $transaction->rollBack();
                return $this->render('create', [
                    'model' => $model,
                    'companyModel' => $companyModel,
                    'authAssignmentModel' => $authAssignmentModel
                ]);
            }

            $modelPoint = new Point();
            $modelPoint->user_id = $model->id;
            $modelPoint->balance = 0;
            $modelPoint->created_at = time();
            $modelPoint->save();

            $transaction->commit();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'companyModel' => $companyModel,
            'authAssignmentModel' => $authAssignmentModel
        ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $authAssignmentModel = $this->findAuthAssignmentModel($id);
        $companyModel = $this->findCompanyModel($model->company);
        if($authAssignmentModel->item_name == "accountant" && !Yii::$app->user->can('create-accountant')) {
            throw new ForbiddenHttpException;
        }
        if($authAssignmentModel->item_name == "admin" && !Yii::$app->user->can('create-user-admin') ) {
            throw new ForbiddenHttpException;
        }

		$user = Yii::$app->request->post('User');
	    $model->password = $user['password'];
	    $model->setPassword($user['password']);
	    $model->generateAuthKey();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->signatureImageFile = UploadedFile::getInstance($model, 'signatureImageFile');

            if($model->save() && $authAssignmentModel->load(Yii::$app->request->post())) {
                $assignment = Yii::$app->request->post('AuthAssignment');

                $authAssignmentModel->user_id = strval($model->id);
                $authAssignmentModel->item_name = $assignment['role'];
                $authAssignmentModel->role = $assignment['role'];
                $authAssignmentModel->save();

            } else {
		return print_r($model->getErrors());
		}

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'companyModel' => $companyModel,
            'authAssignmentModel' => $authAssignmentModel
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $authAssignmentModel = $this->findAuthAssignmentModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUpdatePassword($id) {
        $model = $this->findModel($id);

        if($user = Yii::$app->request->post('User')) {
            $model->password = $user['password'];
            $model->setPassword($user['password']);
            $model->generateAuthKey();
            $model->save();
        }

        return $this->render('view', [
            'model' => $model
        ]);

    }

    /*public function actionAssignPassword() {
        $request = Yii::$app->request;
        $role = $request->get('role');

        foreach(Yii::$app->authManager->getUserIdsByRole($role) as $user) {
            $model = $this->findModel($user);
            if($request->get('password')) {
                $model->password = $request->get('password');
                $model->setPassword($request->get('password'));
                $model->generateAuthKey();
                $model->save();
            }
        }

        echo 'done!';
    }*/

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
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

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCompanyModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            return new Company;
        }
    }

    public function actionAuthentication($id) {
        $modelUser = User::findOne($id);
        $model = new LoginForm();
        $model->username = $modelUser->username;

        $modelPoint = Point::findOne(['user_id'=>$id]);
        $user_balance = $modelPoint->balance;
        $transfer_point = Yii::$app->request->post('credit');
        $balance = $user_balance - $transfer_point;

        if($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                return Json::encode(['user_balance'=>$modelPoint->balance, 'transfer_point'=>$transfer_point, 'balance'=>$balance, 'success'=>true, 'msg'=>'']);
            } else {
                return Json::encode(['user_balance'=>$modelPoint->balance, 'transfer_point'=>$transfer_point, 'balance'=>$balance, 'success'=>false, 'msg'=>$model->getErrors()['password'][0]]);

            }
        } 

        return $this->renderAjax('authentication', [
            'model' => $model
        ]);
    }

    public function actionAssignPasswordByUser() {
        $request = Yii::$app->request;
        $username = $request->get('username');

	$model = User::find()->where(['username'=>$username])->one();
	if($request->get('password')) {
		$model->password = $request->get('password');
		$model->setPassword($request->get('password'));
		$model->generateAuthKey();
		$model->save();
	}

        echo 'done!';
    }

    public function actionAssignPassword() {
        $request = Yii::$app->request;
        $role = $request->get('role');

        foreach(Yii::$app->authManager->getUserIdsByRole($role) as $user) {
            $model = $this->findModel($user);
            if($request->get('password')) {
                $model->password = $request->get('password');
                $model->setPassword($request->get('password'));
                $model->generateAuthKey();
                $model->save();
            }
        }

        echo 'done!';
    }
}
