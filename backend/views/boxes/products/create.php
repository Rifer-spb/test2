<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Entities\Boxes\Boxes;
use common\models\Forms\Boxes\Products\AddForm;

/** @var yii\web\View $this */
/** @var Boxes $box */
/** @var AddForm $model */

$this->title = 'Add product';
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $box->title, 'url' => ['boxes/view', 'id' => $box->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="products-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shipped_qty')->textInput() ?>

        <?= $form->field($model, 'received_qty', [
            'template' => '
                {label}
                <div class="input-group mb-3">
                    {input}
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="copyButton">Match</button>
                    </div>
                </div>
            ',
        ]) ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<?php
$this->registerJs(<<<JS

    $('#copyButton').click(function() {
        $('#addform-received_qty').val($('#addform-shipped_qty').val());
    });

JS
);
