<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-default-index">
    <h1><?= $this->title ?></h1>
    <p>
    <?= Html::a('Авторы', Url::toRoute(['authors/index'])) ?><br>
    <?= Html::a('Категории', Url::toRoute(['categories/index'])) ?><br>
    <?= Html::a('Список статей', Url::toRoute(['items/index'])) ?><br>
    <?= Html::a('Корзина статей', Url::toRoute(['items/basket'])) ?><br>
    </p>
</div>
