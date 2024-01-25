<?php

namespace common\models\Entities\Products;

use common\models\Entities\Boxes\BoxesProduct;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string $title Название
 * @property string|null $sku SKU
 * @property int $shipped_qty Shipped Qty
 * @property int $received_qty Received Qty
 * @property float $price Price
 *
 * @property BoxesProduct $boxProductRelation
 *
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @param string $title
     * @param string $sku
     * @param int $shipped_qty
     * @param int $received_qty
     * @param float $price
     * @return Products
     */
    public static function create(string $title, string $sku, int $shipped_qty, int $received_qty, float $price) : self {
        $model = new static();
        $model->title = $title;
        $model->sku = $sku;
        $model->shipped_qty = $shipped_qty;
        $model->received_qty = $received_qty;
        $model->price = $price;
        return $model;
    }

    /**
     * @param string $title
     * @param string $sku
     * @param int $shipped_qty
     * @param int $received_qty
     * @param float $price
     */
    public function edit(string $title, string $sku, int $shipped_qty, int $received_qty, float $price) {
        $this->title = $title;
        $this->sku = $sku;
        $this->shipped_qty = $shipped_qty;
        $this->received_qty = $received_qty;
        $this->price = $price;
    }

    /**
     * @return ActiveQuery
     */
    public function getBoxProductRelation() : ActiveQuery {
        return $this->hasOne(BoxesProduct::class, ['product' => 'id']);
    }
}
