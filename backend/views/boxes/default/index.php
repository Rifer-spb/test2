<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use common\models\Entities\Boxes\Boxes;

/** @var yii\web\View $this */
/** @var common\models\Forms\Boxes\SearchForm $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $statuses */

$this->title = 'Boxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="boxes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Boxes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => CheckboxColumn::class,
            ],
            'id',
            [
                'attribute' => 'date_created',
                'format' => ['datetime', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'weight',
                'format' => 'raw',
                'value' => function (Boxes $model) {
                    $html = "<form class='changeWeightForm' data-id='" . $model->id . "'>";
                        $html .= Html::activeInput('text',$model,'weight', ['class' => 'form-control']);
                    $html .="</form>";
                    return $html;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Boxes $model) use ($statuses) {
                    if(!count($model->productRelations)) {
                        return "В коробке отсутствуют товары. Изменение статуса навозможно";
                    }
                    $statusArray = ArrayHelper::filter($statuses,[0,1]);
                    print_r($model->existShippedQtyAndReceivedQtyDistinction());
                    if (!$model->weight || $model->existShippedQtyAndReceivedQtyDistinction()) {
                        $statusArray = ArrayHelper::filter($statuses,[0]);
                    }
                    $html = "<form class='changeStatusForm' data-id='" . $model->id . "'>";
                    $html .=  Html::activeDropDownList(
                        $model,
                        'status',
                        ArrayHelper::map($statusArray, 'id', 'name'),
                        [
                            'class' => 'form-control'
                        ]
                    );
                    $html .="</form>";
                    return $html;
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Boxes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
    let urlChangeWeightBox = "<?=Url::to(['change-weight'])?>";
    let urlChangeStatusBox = "<?=Url::to(['change-status'])?>";
</script>

<?php

$this->registerJs(<<<JS

let changeWeightForm = $(".changeWeightForm");
let changeStatusForm = $(".changeStatusForm");

changeWeightForm.on('change', function() {
    const id = $(this).data('id');
    const value = $(this).find('input').val();
    if(!id) {
        return;
    }
    $.post(urlChangeWeightBox + '?id=' + id, {
        value: parseFloat(value),
        _csrf: yii.getCsrfToken(),
    }, function(data) {
        if(data.errorValidate) {
            alert(data.errorValidate);
        }
    });
});

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

?>
