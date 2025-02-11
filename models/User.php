<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;  // Ensure ActiveRecord is imported

/**
 * User model.
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';  // Define the table name
    }

    /**
     * Finds identity by id
     * @param int $id the user ID
     * @return static|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds identity by access token
     * @param string $token the access token
     * @param string $type
     * @return static|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;  // Password validation should use hashing for security
    }

    /**
     * Creates a new user
     * @return bool whether the user was saved successfully
     */
    public function saveUser()
    {
        return $this->save();  // The save method is now available as ActiveRecord is extended
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Hash the password before saving it
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }


    /**
     * Get the user's role
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}