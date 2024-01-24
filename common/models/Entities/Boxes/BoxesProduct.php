<?php

namespace common\models\Entities\Boxes;

use common\models\Entities\Products\Products;

/**
 * This is the model class for table "{{%boxes_product}}".
 *
 * @property int $id
 * @property int $box Коробка
 * @property int $product Продукт
 */
class BoxesProduct extends \yii\db\ActiveRecord
{
    /** @var Products */
    private $productModel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%boxes_product}}';
    }

    /**
     * @param Products $product
     * @return BoxesProduct
     */
    public static function create(Products $product) : self {
        $model = new static();
        $model->productModel = $product;
        return $model;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            if($insert) {
                if(!$this->productModel) {
                    throw new \DomainException('Product not found');
                }
                $this->productModel->save();
                $this->product = $this->productModel->id;
            }
            return true;
        } else {
            return false;
        }
    }
}
