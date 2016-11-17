<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Categories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['categories/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотитте удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'published' => [
                'content' => function ($model, $key, $index, $column) {
                    $publ = $model->published;
                    if ($publ == 1)
                    {
                        return "Да";
                    } else {
                        return "Нет";
                    }
                }
            ],
            'metadesc:ntext',
            'metakey:ntext',            
        ],
    ]) ?>

</div>
