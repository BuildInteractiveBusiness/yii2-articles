<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\helpers\StringHelper;

use robot72\modules\articles\models\Authors;

/* @var $model app\modules\articles\models\Items */
?>
<figure class="span_news">
    <?php echo  Html::img($imageurl.$model->image, ['alt' => "$model->fullname", 'class' => 'avatar']) ?>
    
    <figcaption>
        <a href="<?php echo Url::toRoute(['author', 'id' => $model->id]) ?>">
        <span class="golden underlined"><?php // $model->id ?></span>

        <p><?= $model->fullname ?></p>
      </a>
    </figcaption>

    
</figure>