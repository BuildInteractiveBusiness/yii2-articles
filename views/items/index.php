<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use robot72\modules\articles\models\Categories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\articles\models\SearchCategories */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список статей';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать статью', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            //'introtext',
            'published' => [
                'content' => function($model, $key, $index, $column) {
                    $b = $model->published;
                    if($b == 0) {
                        return "Нет";
                    } else {
                        return "Да";
                    }
                },
                'attribute' => 'Опубликовано?',                
            ],
            'catid' => [
                'content' => function ($model, $key, $index, $column) {
                    return Categories::findOne(['id' => $model->catid])->name;
                },
                'attribute' => 'Категория',
            ],            
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'delete' => function($url, $model, $key) {
                        $url = Url::toRoute(['move-to-basket', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
                    }
                ],
            ],
        ],
    ]); ?>
    
    <p>Вступление - то, что будет отображаться с списке статей, либо аннотация, либо начальная часть статьи</p>
    <p>Полный текст - весь текст статьи</p>
</div>
