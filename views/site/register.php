<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif; ?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'confirmPassword')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Register', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>