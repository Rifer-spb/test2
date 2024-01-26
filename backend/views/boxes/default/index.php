<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\helpers\ArrayHelper;
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

    <?php echo $this->render('_search', ['model' => $searchModel, 'statuses' => $statuses]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function(Boxes $model) {
            if($model->existShippedQtyAndReceivedQtyDistinction()) {
                return ['class' => 'warning'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => CheckboxColumn::class,
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [
                        'value' => $model->id ,
                        'class' => 'checkbox-item',
                    ];
                }
            ],
            [
                'attribute' => 'id',
                'filter' => false
            ],
            [
                'attribute' => 'date_created',
                'format' => ['datetime', 'php:d.m.Y H:i:s'],
                'filter' => false
            ],
            [
                'attribute' => 'weight',
                'format' => 'raw',
                'value' => function (Boxes $model) {
                    $html = "<form class='changeWeightForm' data-id='" . $model->id . "'>";
                        $html .= Html::activeInput('text',$model,'weight', ['class' => 'form-control']);
                    $html .="</form>";
                    return $html;
                },
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Boxes $model) use ($statuses) {
                    if(!count($model->productRelations)) {
                        return "В коробке отсутствуют товары. Изменение статуса навозможно";
                    }
                    $statusArray = ArrayHelper::filter($statuses,[0,1]);
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
                },
                'filter' => false
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {countVolume} {countPrice} {checkDistinction}',
                'buttons' => [
                    'countVolume' => function ($url, $model, $key) {
                        return Html::a('countVolume', $url, ['class' => 'count-volume-button', 'data-id' => $model->id]);
                    },
                    'countPrice' => function ($url, $model, $key) {
                        return Html::a('countPrice', $url, ['class' => 'count-price-button', 'data-id' => $model->id]);
                    },
                    'checkDistinction' => function ($url, $model, $key) {
                        return Html::a('checkDistinction', $url, ['class' => 'check-distinction-button', 'data-id' => $model->id]);
                    },
                ]
            ],
        ],
    ]); ?>

    <div class="change-status-all">
        <label for="change-status-all">Изменить статусы</label>
        <div>
            <select id="change-status-all" class="form-control">
                <option value="">Выберите статус...</option>
                <?php foreach ($statuses as $status) : ?>
                    <option value="<?=$status['id']?>"><?=$status['name']?></option>
                <?php endforeach; ?>
            </select>
            <button id="change-status-all-button" class="btn btn-primary">Изменить</button>
        </div>
    </div>

    <div>
        <button id="export-exel-button" class="btn btn-primary">Выгрузить отчет в эксель</button>
    </div>

</div>
<script type="text/javascript">
    let urlChangeWeightBox = "<?=Url::to(['change-weight'])?>";
    let urlChangeStatusBox = "<?=Url::to(['change-status'])?>";
    let urlChangeStatusALLBox = "<?=Url::to(['change-status-all'])?>";
    let urlExportExelBox = "<?=Url::to(['export-exel'])?>";
    let urlCountVolumeBox = "<?=Url::to(['count-volume'])?>";
    let urlCountPriceBox = "<?=Url::to(['count-price'])?>";
    let urlCheckDistinctionBox = "<?=Url::to(['check-distinction'])?>";
</script>

<?php

$this->registerJs(<<<JS

let changeWeightForm = $(".changeWeightForm");
let changeStatusForm = $(".changeStatusForm");
let changeStatusAllButton = $("#change-status-all-button");
let exportExelButton = $("#export-exel-button");
let boxCountVolumeButton = $(".count-volume-button");
let boxCountPriceButton = $(".count-price-button");
let boxCheckDistinctionButton = $(".check-distinction-button");

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
            alert(data.errorValidate['changeweightform-value'][0]);
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

changeStatusAllButton.on('click', function() {
    const selected = [];
    const select = $(this).parent().find('select');
    $('input.checkbox-item:checked').each(function() {
        selected.push(parseInt($(this).val()));
    });
    if(!selected.length) {
        alert('Для изменения статуса необходимо выбрать коробку');
        select.val('');
        return;
    }
    $.post(urlChangeStatusALLBox, {
        items: selected,
        status: parseInt(select.val()),
        _csrf: yii.getCsrfToken(),
    });
});

exportExelButton.on('click', function() {
    $.post(urlExportExelBox + window.location.search, {
        _csrf: yii.getCsrfToken(),
    }, function(data) {
        if(data) {
            const blob = new Blob([data],{ type: data.type });
            const url = window.URL.createObjectURL(blob, {
            type: data.type
            });
            const a = document.createElement('a');
            a.href = url;
            a.download = "BoxesExport.csv";
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        }
    });
});

boxCountVolumeButton.on('click', function(e) {
    e.preventDefault();
    const id = parseInt($(this).data('id'));
    if(!id) {
        return;
    }
    $.post(urlCountVolumeBox + '?id=' + id, {
        _csrf: yii.getCsrfToken(),
    },function(data) { 
        alert('Объем коробки: ' + data + ' mm');
    });
});

boxCountPriceButton.on('click', function(e) {
    e.preventDefault();
    const id = parseInt($(this).data('id'));
    if(!id) {
        return;
    }
    $.post(urlCountPriceBox + '?id=' + id, {
        _csrf: yii.getCsrfToken(),
    },function(data) { 
        alert('Сумма коробки: ' + data + ' руб');
    });
});

boxCheckDistinctionButton.on('click', function(e) {
    e.preventDefault();
    const id = parseInt($(this).data('id'));
    if(!id) {
        return;
    }
    $.post(urlCheckDistinctionBox + '?id=' + id, {
        _csrf: yii.getCsrfToken(),
    },function(data) {
        if(data) {
            alert('Shipped qty и Received qty не совпадают');
        } else {
            alert('Shipped qty и Received qty совпадают');
        }
    });
});

JS
);


$this->registerCss(<<<CSS

    #w1-filters {
        display: none;
    }
    
    .change-status-all {
        margin-bottom: 20px;
    }
    
    .change-status-all>div {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .change-status-all>div select {
        width: max-content;
        margin-right: 10px;
    }

    tr.warning {
        background-color: #ffe3b1 !important;
    }

CSS
);

?>
