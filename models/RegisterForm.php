<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * RegisterForm is the model behind the registration form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username, password, and confirmPassword are required
            [['username', 'password', 'confirmPassword'], 'required'],
            // username must be unique
            ['username', 'validateUsername'],
            // confirmPassword must match the password
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            // password must be validated
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * @param string $attribute the attribute being validated
     */
    public function validatePassword($attribute)
    {
        if (strlen($this->password) < 6) {
            $this->addError($attribute, 'Password should be at least 6 characters.');
        }
    }

    /**
     * Validates if the username is unique.
     * @param string $attribute the attribute being validated
     */
    public function validateUsername($attribute)
    {
        if (User::findByUsername($this->username)) {
            $this->addError($attribute, 'Username already exists.');
        }
    }

    /**
     * Registers a new user.
     * @return bool whether the user is created successfully
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password = $this->password; // In real use, hash the password before saving
            $user->authKey = Yii::$app->security->generateRandomString();
            $user->accessToken = Yii::$app->security->generateRandomString();
            $user->role = User::ROLE_USER;

            // Save the user in the database
            if ($user->saveUser()) {
                return true;
            } else {
                $this->addError('username', 'Failed to register user.');
            }
        }
        return false;
    }
}