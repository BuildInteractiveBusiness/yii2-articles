<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Categories */

$this->title = 'Обновление категории: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['categories/index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
