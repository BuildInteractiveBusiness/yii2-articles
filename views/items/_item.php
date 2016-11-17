<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\helpers\StringHelper;

use robot72\modules\articles\models\Authors;

/* @var $model app\modules\articles\models\Items */
$preview = '';
if($model->image == null) {
    $preview = Yii::$app->homeUrl.\Yii::$app->params['withoutPreview'];
} else {
    $preview = $imageurl.$model->image;
}
?>
<figure class="span_news">
    <?= Html::img($preview, [
        'alt'   => "$model->title", 
        'class' => 'shadow--white'
        ]) ?>
    
    <figcaption>
        <a href="<?= Url::toRoute(['/'.$model->alias]) ?>">
        <span class="golden underlined"><?= $model->title ?></span>

        <p><?= StringHelper::truncateWords(strip_tags($model->introtext), 25, '', true) ?></p>
      </a>
    </figcaption>

    <div class="news_main">
        <a href="<?= Url::toRoute(['/'.$model->alias]) ?>"><p><?=  StringHelper::truncateWords(strip_tags($model->fulltext), 40, '...', true) ?></p></a>

      <div class="news_links">
            <a href="#"><?= Authors::findOne(['id' => $model->userid])->fullname ?></a>
            <span>l</span>
            <a href="#"><?= $model->category->name ?></a>
            <span>l</span>
            <a href="#"><?= \Yii::$app->formatter->asDate($model->created) ?></a>                    
      </div>
    </div>
</figure>