<?php

use app\models\BookCatalog\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <?php if (Yii::$app->user->can('createBook')) { ?>
        <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'publish_year',
            'isbn',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => static fn(string $action, Book $model) => Url::toRoute([$action, 'id' => $model->id]),
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('viewBooks'),
                    'update' => Yii::$app->user->can('updateBook'),
                    'delete' => Yii::$app->user->can('deleteBook'),
                ],
            ],
        ],
    ]); ?>


</div>
