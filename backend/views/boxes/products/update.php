<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Entities\Boxes\Boxes;

/** @var yii\web\View $this */
/** @var common\models\Entities\Products\Products $model */
/** @var Boxes $box */

$this->title = 'Update Products: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['boxes/index']];
$this->params['breadcrumbs'][] = ['label' => $box->title, 'url' => ['boxes/view', 'id' => $box->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="products-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shipped_qty')->textInput() ?>

        <?= $form->field($model, 'received_qty')->textInput() ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
