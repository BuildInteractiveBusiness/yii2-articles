<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Categories */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'published')->dropDownList(['Нет', 'Да']) ?>

    <?= $form->field($model, 'metadesc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'metakey')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'params')->textInput() ?>
    
    <?= $form->field($model, 'branch')->dropDownList($model->branchNames) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
