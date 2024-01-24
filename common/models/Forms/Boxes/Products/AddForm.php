<?php

namespace common\models\Forms\Boxes\Products;

use common\models\Entities\Products\Products;
use yii\base\Model;

/**
 * Class AddForm
 * @package common\models\Forms\Boxes\Products
 *
 * @property string $title
 * @property integer $shipped_qty
 * @property integer $received_qty
 * @property float $price
 * @property string $sku
 */
class AddForm extends Products
{
    public $title;
    public $shipped_qty = 0;
    public $received_qty = 0;
    public $price = 0;
    public $sku;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','sku','shipped_qty'], 'required'],
            [['shipped_qty'], 'integer', 'min' => 1],
            [['received_qty'], 'integer'],
            [['price'], 'number'],
            [['title', 'sku'], 'string', 'max' => 255],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'sku' => 'Sku',
            'shipped_qty' => 'Shipped Qty',
            'received_qty' => 'Received Qty',
            'price' => 'Price',
        ];
    }
}