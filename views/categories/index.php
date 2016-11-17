<?php

use yii\helpers\Html;
use yii\grid\GridView;

use robot72\modules\articles\models\Categories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\articles\models\SearchCategories */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'alias',
            //'description:ntext',
            //'parent',
             'published' => [
                 'content' => function ($model, $key, $index, $column) {
                    $b = $model->published;
                    if($b == 0) {
                        return "Нет";
                    } else {
                        return "Да";
                    }
                 },
                 'attribute' => "Опубликовано?",   
             ],
            // 'access',
            // 'ordering',
            // 'image',
            // 'image_caption:ntext',
            // 'image_credits',
             'params',
             'metadesc:ntext',
             'metakey:ntext',
            'branch' => [
                'content' => function($model, $key, $index, $column) {
                     $i = $model->branch;
                     switch ($i) {
                        case Categories::BRANCH_EXPERT_OPINION: 
                            return "Мнение экспертов";
                            break;
                        case Categories::BRANCH_NEWS:
                            return "Новости";
                            break;
                        default:
                            return "Не задано";
                     }
                },
                'attribute' => "Разделы (Ветки)", 
            ],                         
            // 'robots',
            // 'author',
            // 'copyright',
            // 'language',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
