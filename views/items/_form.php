<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

// Load Kartik Libraries
use kartik\form\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
//use kartik\widgets\Select2;

// Load Editors Libraries
use dosamigos\ckeditor\CKEditor;
use vova07\imperavi\Widget;

// Load Models
use robot72\modules\articles\models\Categories;
use robot72\modules\articles\models\Authors;

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Items */
/* @var $form kartik\widgets\ActiveForm */

$css = '
.redactor-editor {
    font-size: 14px;
    color: #767676; 
}
.redactor-editor p {
    margin: 15px 0;
}
.redactor-editor p.q {
    position: relative;
    margin-left: 15px;
}
.redactor-editor p.q {
    content: "—";
    position: absolute;
    left: -15px;
}
.redactor-editor .articles_main__title--blue {
    font-size: 22px;
    color: #000059;
}
.redactor-editor blockquote {
    height: auto;
    width: auto;
    padding: 15px 24px;
    background-color: #195cab;
    box-shadow:         3px 3px 1px 0px rgba(50, 50, 50, 0.5);
    -webkit-box-shadow: 3px 3px 1px 0px rgba(50, 50, 50, 0.5);
    -moz-box-shadow:    3px 3px 1px 0px rgba(50, 50, 50, 0.5);
    color: #b0c8e9;
}

.redactor-editor blockquote a {
  color: #FFF;
}

.redactor-editor blockquote a:visited {
  color: #e8eff8;
}

.redactor-editor table {
  height: auto;
  width: 100%;
  margin: 15px 0;
}

.redactor-editor table thead {
  background-color: #d2e0f1;
  color: #4774b5;
}

.redactor-editor table tbody tr:nth-child(odd) {
  background-color: #fff;
  color: #404040;
}

.redactor-editor table tbody tr:nth-child(even) {
  background-color: #0e51a0;
  color: #fff;
}

.redactor-editor table tr {
  line-height: 3em;
}

.redactor-editor table tr td { text-align: center;}

.redactor-editor h1 {
  font-size: 22px;
  color: #000059;
}
.redactor-editor h2 {
  font-size: 20px;
  color: #000059;
}
.redactor-editor h3 {
    font-size: 18px;
    color: #000059;
}
';
$this->registerCss($css);

$imagetype = Yii::$app->controller->module->imagetype;
$imageurl  = Yii::$app->controller->imageUrl;
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'title', [
        'addon' => [
            'prepend' => [
                'content'=>'<i class="glyphicon glyphicon-plus"></i>'
            ]
        ]
    ])->textInput(['maxlength' => 255])->hint('Этот заголовок отображается в теге title и в списке статей. Заголовок в самом тексте статьи задается при помощи кнопки "Форматирование" и выбора соответсвующего h1, h2 или h3.') ?>

    <?= $form->field($model, 'alias', [
        'addon' => [
            'prepend' => [
                'content'=>'<i class="glyphicon glyphicon-globe"></i>'
            ]
        ]
    ])->textInput(['maxlength' => 255])->hint('Url - генерируется автоматически, на основе заголовка в теге title') ?>
    
    <?= $form->field($model, 'introtext')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'toolbarFixed' => false,
            'placeholder' => 'Введите введение',
            'buttons' => [
                'html', 'bold', 'italic', 'deleted', 'link', 
            ],
            'buttonSource' => true,            
        ],
    ])->hint('то, что будет отображаться с списке статей, либо аннотация, либо начальная часть статьи');
    ?>
    
    <?= $form->field($model, 'fulltext')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'toolbarFixed' => true,
            'minHeight' => 200,
            'imageManagerJson' => Url::to(['items/image-get']),
            'imageUpload' => Url::to(['items/image-upload']),
            'formatting' => [
                'blockquote',
            ],            
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent', 
                'image', 'link', 'alignment', 'horizontalrule'
            ],
            'formattingAdd' => [
                [
                    'tag' => 'h1',
                    'title' => 'Заголовок1',                    
                ],
                [
                    'tag' => 'h2',
                    'style' => 'font-size: 20px; color: red;',
                    'title' => 'Заголовок 2',                    
                ],
                [
                    'tag' => 'h3',
                    'style' => 'font-size: 16px; color: #000059;',
                    'title' => 'Заголовок 3',                    
                ],
                [
                    'tag' => 'span',
                    'title' => 'Заголовок 4(span)',
                    'class' => 'articles_main__title--blue',
                ],
                [
                    'tag' => 'p',
                    'title' => 'Текст',
                    'class' => 'q',
                ],
                [
                    'tag' => 'p',
                    'title' => 'Обычный текст',                    
                ],                
            ],
            'buttonSource' => true,
            'plugins' => [
                'imagemanager', 'table', 'fontcolor', 'fontsize', 'bufferbuttons', 'lineheight',
            ]
        ],       
    ])->hint('Весь текст статьи'); ?>
    
    <?= $form->field($model, 'published')->dropDownList([
            1 => 'Опубликовать',
            0 => 'Не публиковать',
    ]) ?>
    
    <?= $form->field($model, 'catid')->dropDownList(ArrayHelper::map(Categories::find()->all(), 'id', 'name'))->label('Категория'); ?>
    
    <?= $form->field($model, 'userid')->dropDownList(ArrayHelper::map(Authors::find()->all(), 'id', 'fullname'))->label('Автор'); ?>
    
    <?= $form->field($model, 'metadesc', [
    'addon' => [
        'prepend' => [
            'content'=>'<i class="glyphicon glyphicon-info-sign"></i>'
        ]
    ]
    ])->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'metakey', [
        'addon' => [
            'prepend' => [
                'content'=>'<i class="glyphicon glyphicon-tags"></i>'
            ]
        ]
    ])->textarea(['rows' => 4]) ?>
    
    <?= $form->field($model, 'created')->widget(DateTimePicker::className(), [
        'size' => 'sm',
    ])->label('Дата создания') ?>
    
    <?= $form->field($model, 'modified')->widget(DateTimePicker::className(), [
        'size' => 'sm',
    ])->label('Дата редактирования') ?>
    
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
        ])->hint('Рекоммендуемый размер изображения 100x100.');?>

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
                <p class="glyphicon glyphicon-alert">Рекоммендуемый размер изображения 870x450.</p>
            </div>
        </div>
        
    <?php }  ?>

    
        <?= $form->field($model, 'image_caption', [
            'addon' => [
                'prepend' => [
                    'content'=>'<i class="glyphicon glyphicon-picture"></i>'
                ]
             ]
        ])->textarea(['rows' => 6]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
