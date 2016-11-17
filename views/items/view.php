<?php

use yii\helpers\Html;
use yii\helpers\Url;
use robot72\modules\articles\models\Authors;

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Items */
// SEO Parameters
\Yii::$app->params['MainMenu'] = 4;
$this->title = $model->title;
if ($model->metadesc) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $model->metadesc,
    ]);
}
if ($model->metakey) {
    $this->registerMetaTag([
        'name' => 'keywords',
        'content' => $model->metakey
    ]);
}

$curr_url = Yii::$app->request->url;
$this->registerJs("function add2Fav(x){ if (document.all && !window.opera) { if (typeof window.external == \"object\") { window.external.AddFavorite (document.location, document.title); return false; } else return false; } else { var ua = navigator.userAgent.toLowerCase(); var isWebkit = (ua.indexOf('webkit') != - 1); var isMac = (ua.indexOf('mac') != - 1); if (isWebkit || isMac) { alert('Нажмите ' + (isMac ? 'Command/Cmd' : 'CTRL') + ' + D для добавления в избранное'); return false; } else { x.href=document.location; x.title=document.title; x.rel = \"sidebar\"; return true; } } }", \yii\web\View::POS_HEAD);
$params = Yii::$app->params;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get info by Configuration
$imagetype = Yii::$app->controller->module->imagetype;
$imageurl = Yii::$app->homeUrl . Yii::$app->controller->module->imagepath;
?>

<section class="nav_span">

    <div class="wr">
        <ul class="nav_span__add">
            <li><?= Html::a('Все статьи', Url::toRoute('/articles/items/all'), ['class' => 'active']) ?></li>
            <li><a href="<?= Url::toRoute('/articles/items/expert-opinion') ?>">Мнение экспертов</a></li>
            <li><a href="<?= Url::toRoute('/articles/items/news') ?>">Новости</a></li>
            <li><a href="#">Блоги</a></li>
        </ul>
    </div>

</section>

<section class="content__top"id="sec1">
    <div class="wr">
        <div class="center">

            <div class="back_links">
                <?= Html::a('Главная', Url::toRoute('/site/index')) ?>
                <span class="back_links__divider">&#8594;</span>
                <?= Html::a('Все статьи', Url::toRoute('/articles/items/all')) ?>
                <span class="back_links__divider">&#8594;</span>
                <?= Html::a($model->category->name, Url::toRoute(['/articles/items/category', 'id' => $model->catid])) ?>
                <span class="back_links__divider">&#8594;</span>
                <?= Html::a($model->title, Yii::$app->request->url) ?>
            </div>
            <div class="clr"></div>

        </div>

        <section class="articles">
            <div class="wr">
                <section class="articles_list">
                    <section class="articles_main">

                        <article>
                            <?= $model->fulltext ?>
                            <?php if ($model->image != null) {
                                ?>
                                <figure class="pic_text">

                                    <?=
                                    Html::img($imageurl . $model->image, [
                                        'alt' => "$model->title",
                                        'class' => 'shadow--dark_blue-short'
                                    ]);
                                    ?>

                                    <figcaption>
                                        <a href="#"><?= $model->image_caption ?></a>
                                    </figcaption>
                                </figure>
                            <?php } ?>
                        </article>

                    </section>

                    <section class="articles_r_side">

                        <div class="helper">
                            <ul>
                                <li>Автор</li>
                                <li><a href="#"><?= Authors::findOne(['id' => $model->userid])->fullname ?></a></li>
                                <li>Дата публикации</li>
                                <li><a href="#"><?= Yii::$app->formatter->asDate($model->created) ?></a></li>
                                <li>Категория: </li>
                                <li><a href="#"><?= $model->category->name ?></a></li>
                                <li>Поделиться статьёй</li>
                                <li>
                                    <div class="social">
                                        <ul>
                                            <li class="vk"><?= Html::a('', $params['vk'] . Yii::$app->request->url, ['rel' => 'nofollow']) ?></li>
                                            <li class="facebook"><?= Html::a('', $params['fb'] . Yii::$app->request->url, ['rel' => 'nofollow']) ?></li>
                                            <li class="twitter"><?= Html::a('', $params['tw'] . Yii::$app->request->url, ['rel' => 'nofollow']) ?></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="" onclick="add2Fav(this)" class="underlined">Добавить в избранное</a></li>
                            </ul>
                        </div>
                        <div class="clr"></div>
                        <!--
                                                <div class="anc3">
                                                  <a href="#sec1">
                                                    <img src="<?php //Yii::$app->homeUrl  ?>img/aside_logo_bg.png" alt="">
                                                    <span>Наверх</span>
                                                  </a>
                                                </div>

                                              <div class="anc2">
                                                <a href="#sec1">
                                                  <img src="<?php //echo Yii::$app->homeUrl  ?>img/aside_logo_bg.png" alt="">
                                                  Наверх
                                                </a>
                                              </div> -->


                        <div class="anc shadow--dark_blue-short" id="button-link-top">
                            <a href="#sec1">Наверх</a>
                        </div>

                    </section>
                </section>
            </div>
        </section>

    </div>
</section>