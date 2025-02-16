<?php

declare(strict_types=1);

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BookCatalog\BookUploadForm $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array<int, string> $authorsList */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->book, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->book, 'publish_year')->textInput() ?>
    <?= $form->field($model->book, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model->book, 'isbn')->textInput(['maxlength' => 13]) ?>
    <?= $form->field($model->book, 'authorsId')->widget(Select2::class, [
        'data' => $authorsList,
        'options' => ['multiple' => true],
    ]) ?>
    <?= $form->field($model, 'bookCoverImg')->fileInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
