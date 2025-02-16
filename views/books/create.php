<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\BookUploadForm $model */
/** @var array<int, string> $authorsList */

$this->title = 'Новая книга';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">

    <?= $this->render('_form', [
        'model' => $model,
        'authorsList' => $authorsList,
    ]) ?>

</div>
