<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Entities\Boxes\Boxes $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="boxes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add-product', ['products/add', 'boxId' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'date_created',
            'weight',
            'width',
            'length',
            'height',
            'reference',
            'status',
        ],
    ]) ?>

</div>
