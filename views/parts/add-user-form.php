<?php

use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['action'=>'users/add-user', 'method'=>'POST', 'options'=>['name'=>'user-add-form']]); ?>
    <?= $form->field($model, 'login'); ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= $form->field($model, 'name'); ?>
    <input type="submit" value="Send">
<?php $form::end(); ?>
