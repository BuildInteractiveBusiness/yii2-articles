<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use robot72\modules\articles\models\ItemsLinkSorter;
use robot72\modules\articles\widgets\ArticlesWidget;
use robot72\widgets\PoolWidget;

$params = Yii::$app->params;
\Yii::$app->params['MainMenu'] = 4;
$imagetype = Yii::$app->controller->module->imagetype;
$imageurl  = Yii::$app->homeUrl.Yii::$app->controller->module->thumbpath;
?>

<section class="nav_span">

<div class="wr">
  <ul class="nav_span__add">
    <li><?= Html::a('Все статьи', Url::toRoute('/articles/items/all'), ['class' => 'active']) ?></li>
    <li><a href="<?= Url::toRoute('/articles/items/expert-opinion') ?>">Мнение экспертов</a></li>
    <li><a href="<?= Url::toRoute('/articles/items/news') ?>">Новости</a></li>
    <li><a href="<?= Url::toRoute('/articles/items/blogs') ?>">Блоги</a></li>
  </ul>
</div>

</section>

<section class="content__top">
<div class="wr">
  <div class="center">

    <div class="back_links">
      <?= Html::a('Главная', Url::toRoute('/site/index')) ?>
      <span class="back_links__divider">&#8594;</span>
      <?= Html::a('Все статьи', Url::toRoute('/articles/items/all')) ?>
    </div>
    <div class="clr"></div>

    <section class="articles">

    <aside class="categories">
      <span class="title--white">По категориям</span>

      <ul class="categories_list">
        <li>
            <?= Html::a('Все статьи', Url::toRoute('/articles/items/all')) ?>
        </li>

        <?php foreach ($categories as $c) { ?>
        <li <?php if(\Yii::$app->request->get('id') == $c->id) { echo 'class="active"'; } ?>>
            <?= Html::a($c->name, Url::toRoute(['/articles/items/category','id' => $c->id])) ?>
        </li>
        <?php } ?>        
      </ul>

      <?= $this->render('social') ?>

    </aside>



      <div class="random_articles">

        <?php // ArticlesWidget::widget() ?>
          <?= app\widgets\HotOffersWidget::widget(); ?>

        <?= PoolWidget::widget() ?>

      </div>

      <div class="news_section">

      <span class="title--white">Все статьи</span>
           
      <?= ListView::widget([
          'dataProvider' => $dataProvider,
          'itemView' => '_item',
          'viewParams' => [
              'imagetype' => $imagetype,
              'imageurl'  => $imageurl,
          ],
          'layout' => '<div class="fast_nav">{sorter}</div>{items}<div class="pages">{pager}</div>',
          'sorter' => [
              'class' => ItemsLinkSorter::className(),
              'attributes' => [
                  'created',
                  'userid',
              ],              
          ],
      ]) ?>
      
      </div>

    </section>

  </div>
</div>
</section>