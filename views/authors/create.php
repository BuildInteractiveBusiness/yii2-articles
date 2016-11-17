<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\articles\models\Authors */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
