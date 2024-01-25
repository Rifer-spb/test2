<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Entities\Products\Products;
use common\models\Entities\Boxes\Boxes;

/** @var yii\web\View $this */
/** @var common\models\Entities\Boxes\Boxes $box */
/** @var common\models\Forms\Boxes\Products\SearchForm $searchBoxesForm */
/** @var yii\data\ActiveDataProvider $dataProductProvider */
/** @var array $statuses */
$this->title = $box->title;
$this->params['breadcrumbs'][] = ['label' => 'Boxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="boxes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add-product', ['boxes/products/add', 'boxId' => $box->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $box,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'date_created',
                'format' => ['datetime', 'php:d.m.Y H:i:s']
            ],
            [
                'label' => 'Count products',
                'value' => function ($model) {
                    return count($model->productRelations);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Boxes $model) use ($statuses) {
                    if(!count($model->productRelations)) {
                        return "В коробке отсутствуют товары. Изменение статуса навозможно";
                    }
                    $html = "<form id='changeStatusForm' data-id='" . $model->id . "'>";
                        $html .=  Html::activeDropDownList(
                            $model,
                            'status',
                            ArrayHelper::map(ArrayHelper::filter($statuses,[2,3]), 'id', 'name'),
                            [
                                'class' => 'form-control'
                            ]
                        );
                    $html .="</form>";
                    return $html;
                }
            ],
        ],
    ]) ?>

    <div class="products">
        <h2>Products</h2>
        <?= GridView::widget([
            'dataProvider' => $dataProductProvider,
            'filterModel' => $searchBoxesForm,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                'sku',
                'shipped_qty',
                'received_qty',
                'price',
                [
                    'class' => ActionColumn::class,
                    'urlCreator' => function ($action, Products $model, $key, $index, $column) use ($box) {
                        return Url::toRoute(["boxes/products/$action", 'boxId' => $box->id, 'productId' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>

</div>

<script type="text/javascript">
    let urlChangeStatusBox = "<?=Url::to(['change-status'])?>";
</script>

<?php

$this->registerJs(<<<JS

let changeStatusForm = $("#changeStatusForm");

changeStatusForm.on('change', function() {
    const id = $(this).data('id');
    const status = $(this).find('select').val();
    if(!id) {
        return;
    }
    $.post(urlChangeStatusBox + '?id=' + id, {
        status: parseInt(status),
        _csrf: yii.getCsrfToken(),
    });
});

JS
);

$this->registerCss(<<<CSS

    #changeStatusForm {
        max-width: 200px
    }    

CSS
);
?>
