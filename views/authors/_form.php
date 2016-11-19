<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model robot72\modules\articles\models\Categories */

$imagetype = Yii::$app->controller->module->imagetype;
$imageurl  = Yii::$app->controller->imageUrl;
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?php if ($model->image==""){ ?>

        <?= $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => [
                'accept' => 'image/'.$imagetype
            ],
            'pluginOptions' => [
                'previewFileType' => 'image',
                'showUpload' => false,
                'browseLabel' => 'Открыть',
            ],
        ])->hint('Рекоммендуемый размер изображения 200x200.');?>

    <?php } else { 	?>		
                            
        <?= $form->field($model, 'image')->hiddenInput() ?>

        <div class="thumbnail">                       	
            <img alt="200x200" class="img-thumbnail" data-src="holder.js/300x250" style="width: 300px;" src="<?= $imageurl.$model->image ?>">
            <div class="caption">
                <p></p>
                <p>
                    <a class="btn btn-danger" href="deleteimage?id=<?= $model->id ?>">
                        Удалить изображение
                    </a> 
                </p>
                <p class="glyphicon glyphicon-alert">Рекоммендуемый размер изображения 200x200.</p>
            </div>
        </div>
        
    <?php }  ?>
    
    <?= $form->field($model, 'fullname')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
