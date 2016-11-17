<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
/* @var $models app\modules\articles\models\Items */

$imagetype = Yii::$app->controller->module->imagetype;
$imageurl  = Yii::$app->homeUrl.Yii::$app->controller->module->thumbpath;

foreach ($models as $m) {
    
    $preview = '';
    if($m->image == null) {
        $preview = Yii::$app->homeUrl.\Yii::$app->params['withoutPrevWidget'];
    } else {
        $preview = $imageurl.$m->image;
    }   
    ?>
<figure class="random_bl shadow--white">
    <a href="<?= Url::toRoute(['/'.$m->alias]) ?>">
      <img src="<?= $imageurl.$m->image ?>" alt="">

      <figcaption>
        <span class="golden"><?= $m->title ?></span>

        <p><?= StringHelper::truncate($m->introtext, 33, '', null, true) ?></p>
        <?= Html::a('Читать полностью', Url::toRoute(['/'.$m->alias])) ?>
      </figcaption>
    </a>
</figure>
<?php }
