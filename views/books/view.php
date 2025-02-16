<?php

declare(strict_types=1);

use app\models\BookCatalog\Book;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\Book $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="book-view">

    <p>
        <?php if (Yii::$app->user->can('updateBook')) { ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php } ?>
        <?php if (Yii::$app->user->can('deleteBook')) { ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'publish_year',
            'description:ntext',
            'isbn',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'cover_photo_path',
                'format' => 'html',
                'value' => function (Book $model) {
                    return Html::img(['download-cover', 'id' => $model->id], ['width' => '200px']);
                }
            ]
        ],
    ]) ?>

</div>
