<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class AdminController extends Controller
{
    /**
     * Displays the admin dashboard.
     */
    public function actionDashboard()
    {
        // Only allow access to admin users
        if (Yii::$app->user->identity->getRole() !== User::ROLE_ADMIN) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        return $this->render('dashboard');  // Render the admin dashboard view
    }

    /**
     * Lists all users.
     * @return mixed
     */
    public function actionUsers()
    {
        // Only allow access to admin users
        if (Yii::$app->user->identity->getRole() !== User::ROLE_ADMIN) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('users', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Edits a user's details (e.g., changing their role).
     * @param int $id User ID
     * @return mixed
     */
    public function actionEdit($id)
    {
        $user = $this->findUser($id);

        // Only allow access to admin users
        if (Yii::$app->user->identity->getRole() !== User::ROLE_ADMIN) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', 'User updated successfully.');
            return $this->redirect(['users']);
        }

        return $this->render('edit', [
            'user' => $user,
        ]);
    }

    /**
     * Creates a new user.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User();

        // Only allow access to admin users
        if (Yii::$app->user->identity->getRole() !== User::ROLE_ADMIN) {
            throw new ForbiddenHttpException('You are not authorized to access this page.');
        }

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', 'User created successfully.');
            return $this->redirect(['users']);
        }

        return $this->render('create', [
            'user' => $user,
        ]);
    }



    /**
     * Finds a user by ID.
     * @param int $id User ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUser($id)
    {
        if (($user = User::findOne($id)) !== null) {
            return $user;
        }

        throw new NotFoundHttpException('The requested user does not exist.');
    }
}