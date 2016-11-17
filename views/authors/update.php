<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Редактировать имя автора: ' . ' ' . $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
