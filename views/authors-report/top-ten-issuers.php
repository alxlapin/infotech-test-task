<?php

declare(strict_types=1);

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\Report\TopTenIssuersReportForm $model */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = "ТОП 10 авторов, выпустивших больше книг за $model->year год";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-ten-issuers-search">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'year') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="top-ten-issuers-report">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'fullname',
            'booksCount',
        ],
    ]); ?>

</div>
