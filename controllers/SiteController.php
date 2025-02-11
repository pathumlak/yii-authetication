<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            // Access Control: Control access to actions based on roles
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'dashboard', 'users', 'edit'],  // Specify actions to control access for
                'rules' => [
                    [
                        'actions' => ['logout'],  // Allow authenticated users to log out
                        'allow' => true,
                        'roles' => ['@'],  // Only authenticated users (logged in) can log out
                    ],
                    [
                        'actions' => ['dashboard'],  // Only allow admins to access the dashboard
                        'allow' => true,
                        'roles' => ['admin'],  // Only users with the 'admin' role can access the dashboard
                    ],
                    [
                        'actions' => ['users', 'edit'],  // Allow admins to manage users
                        'allow' => true,
                        'roles' => ['admin'],  // Only admins can view and manage users
                    ],
                    [
                        'actions' => ['index', 'about', 'contact'],  // Public pages can be accessed by anyone
                        'allow' => true,
                        'roles' => ['?'],  // This allows guests to access these actions
                    ],
                ],
            ],
            // VerbFilter: Restrict HTTP methods for actions
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],  // Only allow POST request for logout
                    'delete' => ['post'],  // Only allow POST request for delete
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registration successful!');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }
}