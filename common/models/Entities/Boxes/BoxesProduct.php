<?php

namespace common\models\Entities\Boxes;

use common\models\Entities\Products\Products;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%boxes_product}}".
 *
 * @property int $id
 * @property int $box Коробка
 * @property int $product Продукт
 *
 * @property Products $productRelation
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
     * @return Products
     */
    public function getProduct() {
        if(!$this->productRelation) {
            throw new \DomainException('Product not found');
        }
        return $this->productRelation;
    }

    /**
     * @return ActiveQuery
     */
    public function getProductRelation() : ActiveQuery {
        return $this->hasOne(Products::class, ['id' => 'product']);
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
