<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\Author $model */

$this->title = 'Новый автор';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
