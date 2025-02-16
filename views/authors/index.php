<?php

declare(strict_types=1);

use app\models\BookCatalog\Author;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\AuthorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <?php if (Yii::$app->user->can('createAuthor')) { ?>
        <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fullname',
//            'created_at:datetime',
//            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {subscribe}',
                'urlCreator' => static fn(string $action, Author $model) => Url::toRoute([$action, 'id' => $model->id]),
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('viewAuthors'),
                    'update' => Yii::$app->user->can('updateAuthor'),
                    'delete' => Yii::$app->user->can('deleteAuthor'),
                ],
                'buttons' => [
                    'subscribe' => function ($url) {
                        return Yii::$app->user->can('subscribeToAuthor') ? Html::a('Подписаться', $url) : '';
                    }
                ]
            ],
        ],
    ]); ?>


</div>
