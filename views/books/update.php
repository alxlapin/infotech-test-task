<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\BookUploadForm $model */
/** @var array<int, string> $authorsList */

$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book->name, 'url' => ['view', 'id' => $model->book->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="book-update">

    <?= $this->render('_form', [
        'model' => $model,
        'authorsList' => $authorsList,
    ]) ?>

</div>
