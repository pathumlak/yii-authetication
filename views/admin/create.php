<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

<?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>

<?= $form->field($user, 'role')->dropDownList([
    'user' => 'User',
    'admin' => 'Admin',
], ['prompt' => 'Select Role']) ?>

<div class="form-group">
    <?= Html::submitButton('Create User', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>