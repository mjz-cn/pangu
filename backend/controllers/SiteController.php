<?php
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\Tree;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'test', 't1'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionTest() {
//        $countries = new Tree(['name' => 'testRoot1']);
//        $countries->makeRoot();
//
//        $testTreeLeft1 = new Tree(['name' => 'testTreeLeft1']);
//        $testTreeLeft1->appendTo($countries);
//
//        $testTreeRight1 = new Tree(['name' => 'testTreeRight1']);
//        $testTreeRight1->appendTo($countries);
        $testTreeLeft1 = Tree::findOne(['name' => 'testTreeLeft1']);

        $russia = new Tree(['name' => 'testTreeLeft12']);
        $russia->appendTo($testTreeLeft1);
//        $testTreeLeft1->appendTo($countries);

//        $russia = new Tree(['name' => 'testTreeRight2']);
//        $russia->appendTo($testTreeRight1);
    }

    public function actionT1()  {
        $testRoot = Tree::findOne(['name' => 'testTreeRight1']);
        $children = $testRoot->prev()->all();


//        var_dump($testRoot);
        var_dump($children);

//        var_dump($children[0]->children(1)->all());
    }

    /**
     * @inheritdoc
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
        return $this->render('index');
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

        $this->layout = false;

        $model = new LoginForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), 'info') && $model->login()) {
                return 1;
            } else {
                return 0;
            }
        } else {
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
