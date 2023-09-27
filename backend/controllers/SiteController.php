<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Product;
use common\models\Quotation;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $connection = Yii::$app->db;

        $productModel = Product::find()->orderBy('id DESC')->limit(5)->all();
        $quotationModel = Quotation::find()->where('MONTH(FROM_UNIXTIME(created_at))=MONTH(NOW()) AND YEAR(FROM_UNIXTIME(created_at))=YEAR(NOW())')->orderBy('id DESC')->limit(5)->all();

        $quotationIssued = $connection->createCommand("SELECT 
            count(*) 
            FROM 
                quotation 
            WHERE 
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())
            
            ")->queryScalar();

        $total_orders = $connection->createCommand("SELECT
            count(*)
            FROM 
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())
                AND status IN (10,11,12)
        ")->queryScalar();

        $total_profit_prev = $connection->createCommand("SELECT
            SUM(total_price_after_disc-total_cost)
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW() - INTERVAL 1 MONTH)
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW() - INTERVAL 1 MONTH)

        ")->queryScalar();

        $total_profit = $connection->createCommand("SELECT
            SUM(total_price_after_disc-total_cost)
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())

        ")->queryScalar();

        $total_profit_perc = $total_profit ? ($total_profit - $total_profit_prev)/$total_profit : 0;

        $total_profit_byday = $connection->createCommand("SELECT
            SUM(total_price_after_disc-total_cost) AS total_profit, ANY_VALUE(DATE_FORMAT(FROM_UNIXTIME(created_at), '%d-%m-%Y')) AS date
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())
            GROUP BY DATE_FORMAT(FROM_UNIXTIME(created_at), '%d')

        ")->queryAll();

        $total_revenue_prev = $connection->createCommand("SELECT
            SUM(total_price_after_disc)
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW() - INTERVAL 1 MONTH)
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW() - INTERVAL 1 MONTH)
        ")->queryScalar();

        $total_revenue = $connection->createCommand("SELECT
            SUM(total_price_after_disc)
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())
        ")->queryScalar();

        $total_revenue_perc = $total_revenue ? ($total_revenue - $total_revenue_prev)/$total_revenue : 0;

        $total_revenue_byday = $connection->createCommand("SELECT
            SUM(total_price_after_disc) AS total_price, ANY_VALUE(DATE_FORMAT(FROM_UNIXTIME(created_at), '%d-%m-%Y')) AS date
            FROM
                quotation
            WHERE
                MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())
                AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW())
            GROUP BY DATE_FORMAT(FROM_UNIXTIME(created_at), '%d')
        ")->queryAll();

        $total_cost_prev = $connection->createCommand("SELECT
            SUM(total_cost)
            FROM
                quotation
            WHERE
                MONTH(created_at) = MONTH(NOW() - INTERVAL 1 MONTH)
                AND YEAR(created_at) = YEAR(NOW() - INTERVAL 1 MONTH)
        ")->queryScalar();

        $total_cost = $connection->createCommand("SELECT
            SUM(total_cost)
            FROM
                quotation
            WHERE
                MONTH(created_at) = MONTH(NOW())
                AND YEAR(created_at) = YEAR(NOW())
        ")->queryScalar();

        $total_cost_perc = $total_cost ? ($total_cost - $total_cost_prev)/$total_cost : 0;

        $total_cost_byday = $connection->createCommand("SELECT
            SUM(total_cost) AS total_cost, ANY_VALUE(DATE_FORMAT(FROM_UNIXTIME(created_at), '%d-%m-%Y')) AS date
            FROM
                quotation
            WHERE
                MONTH(created_at) = MONTH(NOW())
                AND YEAR(created_at) = YEAR(NOW())
            GROUP BY DATE_FORMAT(FROM_UNIXTIME(created_at), '%d')
        ")->queryAll();

        return $this->render('index', [
            'products'=>$productModel,
            'quotations'=>$quotationModel,
            'quotation_issued'=>$quotationIssued,
            'total_orders'=>$total_orders,
            'total_profit'=>$total_profit,
            'total_revenue'=>$total_revenue,
            'total_cost'=>$total_cost,
            'total_profit_perc'=>$total_profit_perc,
            'total_revenue_perc'=>$total_revenue_perc,
            'total_cost_perc'=>$total_cost_perc,
            'total_profit_byday'=>$total_profit_byday,
            'total_revenue_byday'=>$total_revenue_byday,
            'total_cost_byday'=>$total_cost_byday
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
