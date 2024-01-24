<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Forms\Boxes\EditForm $model */
/** @var array $statuses */
/** @var int $id */

$this->title = 'Update Boxes: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="boxes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="boxes-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'weight')->textInput() ?>

        <?= $form->field($model, 'width')->textInput() ?>

        <?= $form->field($model, 'length')->textInput() ?>

        <?= $form->field($model, 'height')->textInput() ?>

        <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList(
            ArrayHelper::map($statuses, 'id','name'),
            ['prompt' => [
                'text' => 'Не выбрано',
                'options' => [
                    'value' => 0
                ]
            ]]    // options
        ); ?>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
