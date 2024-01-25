<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Forms\Boxes\AddForm $model */
/** @var array $statuses */

$this->title = 'Create Boxes';
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="boxes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="boxes-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'weight')->textInput() ?>

        <?= $form->field($model, 'width')->textInput() ?>

        <?= $form->field($model, 'length')->textInput() ?>

        <?= $form->field($model, 'height')->textInput() ?>

        <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
