<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Forms\Boxes\SearchForm $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $statuses */
?>

<div class="boxes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date_from')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'dd-MM-yyyy',
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'Select date'
        ]
    ]) ?>

    <?= $form->field($model, 'date_to')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'dd-MM-yyyy',
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'Select date'
        ]
    ]) ?>

    <?= $form->field($model, 'search') ?>

    <?= $form->field($model, 'status')->dropDownList(
        ArrayHelper::map($statuses, 'id','name'),
        ['prompt' => [
            'text' => 'Не выбрано',
            'options' => [
                'value' => ''
            ]
        ]]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
