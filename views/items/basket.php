<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use robot72\modules\articles\models\Categories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\articles\models\SearchCategories */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Корзина статей';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            //'introtext',            
            'catid' => [
                'content' => function ($model, $key, $index, $column) {
                    return Categories::findOne(['id' => $model->catid])->name;
                },
                'attribute' => 'Категория',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        $url = Url::toRoute(['move-from-basket', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', $url, ['title' => 'Восстановить']);
                    },
                    'delete' => function($url, $model, $key) {
                        $url = Url::toRoute(['delete', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>', $url, ['title' => 'Удалить совсем']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
