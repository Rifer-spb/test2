<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Entities\Boxes\Box $box */
/** @var common\models\Entities\Products\Products $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['boxes/index']];
$this->params['breadcrumbs'][] = ['label' => $box->title, 'url' => ['boxes/view', 'id' => $box->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['boxes/products/update', 'boxId' => $box->id, 'productId' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['boxes/products/delete', 'boxId' => $box->id, 'productId' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'sku',
            'shipped_qty',
            'received_qty',
            'price',
        ],
    ]) ?>

</div>
