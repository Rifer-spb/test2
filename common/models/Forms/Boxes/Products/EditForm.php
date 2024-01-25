<?php

namespace common\models\Forms\Boxes\Products;

use common\models\Entities\Products\Products;

/**
 * Class AddForm
 * @package common\models\Forms\Boxes\Products
 *
 * @property integer $id
 * @property string $title
 * @property integer $shipped_qty
 * @property integer $received_qty
 * @property float $price
 * @property string $sku
 */
class EditForm extends Products
{
    public $id;
    public $title;
    public $shipped_qty;
    public $received_qty;
    public $price;
    public $sku;

    /**
     * EditForm constructor.
     * @param Products $product
     * @param array $config
     */
    public function __construct(Products $product, $config = []) {
        $this->id = $product->id;
        $this->title = $product->title;
        $this->shipped_qty = $product->shipped_qty;
        $this->received_qty = $product->received_qty;
        $this->price = $product->price;
        $this->sku = $product->sku;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','title','sku','shipped_qty'], 'required'],
            [['shipped_qty'], 'integer', 'min' => 1],
            [['id','received_qty'], 'integer'],
            [['price'], 'number'],
            [['title', 'sku'], 'string', 'max' => 255],
            [['sku'], 'skuValidate'],
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

    /**
     * @param $attribute
     * @param $params
     */
    public function skuValidate($attribute, $params) {
        if (!$this->hasErrors()) {
            $existProduct = Products::find()->where([
                'AND',
                ['sku' => $this->sku],
                ['!=','id',$this->id]
            ])->exists();
            if($existProduct) {
                $this->addError($attribute, 'Sku "' . $this->sku . '" has already been taken.');
            }
        }
    }
}