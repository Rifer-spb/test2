<?php

namespace common\models\Entities\Boxes;

use yii\db\ActiveQuery;
use common\models\Entities\Products\Products;
use common\models\Entities\Behaviors\SaveRelationsBehavior;

/**
 * This is the model class for table "{{%boxes}}".
 *
 * @property int $id
 * @property string $title Название
 * @property int $date_created Дата создания
 * @property float $weight Вес
 * @property float $width Ширина
 * @property float $length Длина
 * @property float $height Высота
 * @property string $reference Инфо
 * @property int $status Статус
 *
 * @property BoxesProduct[] $productRelations
 */
class Boxes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%boxes}}';
    }

    /**
     * @return array[]
     */
    public function behaviors() {
        return [
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['productRelations'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules() {
        return [
            [['productRelations'], 'safe']
        ];
    }

    /**
     * @param Box $box
     * @return Boxes
     */
    public static function create(Box $box) : self {
        $model = new static();
        $model->title = $box->title;
        $model->date_created = time();
        $model->weight = $box->weight;
        $model->width = $box->width;
        $model->length = $box->length;
        $model->height = $box->height;
        $model->reference = $box->reference;
        return $model;
    }

    /**
     * @param Box $box
     */
    public function edit(Box $box) {
        $this->title = $box->title;
        $this->weight = $box->weight;
        $this->width = $box->width;
        $this->length = $box->length;
        $this->height = $box->height;
        $this->reference = $box->reference;
        $this->status = $box->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status) {
        $this->status = $status;
    }

    /**
     * @param int $value
     */
    public function setWeight(float $value) {
        $this->weight = $value;
    }

    /**
     * @param string $title
     * @param string $sku
     * @param int $shipped_qty
     * @param int $received_qty
     * @param float $price
     */
    public function addProduct(string $title, string $sku, int $shipped_qty, int $received_qty, float $price) {

        $productRelations = $this->productRelations;
        $productRelations[] = BoxesProduct::create(
            Products::create(
                $title,
                $sku,
                $shipped_qty,
                $received_qty,
                $price
            )
        );
        $this->productRelations = $productRelations;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $sku
     * @param int $shipped_qty
     * @param int $received_qty
     * @param float $price
     */
    public function editProduct(int $id, string $title, string $sku, int $shipped_qty, int $received_qty, float $price) {
        $product = $this->getProduct($id);
        $product->edit(
            $title,
            $sku,
            $shipped_qty,
            $received_qty,
            $price
        );
        if(!$product->save()) {
            throw new \DomainException('Product save error');
        }
    }

    /**
     * @param int $id
     * @return Products
     */
    public function getProduct(int $id) {
        $productRelations = $this->productRelations;
        foreach ($productRelations as $productRelation) {
            if($productRelation->product == $id) {
                return $productRelation->getProduct();
            }
        }
        throw new \DomainException('Product not found');
    }

    /**
     * @param int $id
     * @return Products
     */
    public function findProduct(int $id) {
        $productRelations = $this->productRelations;
        foreach ($productRelations as $productRelation) {
            if($productRelation->product == $id) {
                return $productRelation->getProduct();
            }
        }
    }

    /**
     * @return bool
     */
    public function isStatusAtWarehouse() : bool {
        return $this->status == BoxesStatus::AT_WAREHOUSE;
    }

    /**
     * @return bool
     */
    public function existShippedQtyAndReceivedQtyDistinction() : bool {
        return Products::find()
            ->alias('p')
            ->where(['p.id' => $this->getProductRelations()->select('product')->column()])
            ->andWhere('NOT EXISTS (SELECT * FROM ' . Products::tableName() . ' pp WHERE pp.received_qty!=p.shipped_qty AND pp.id=p.id)')
            ->exists();
    }

    /**
     * @return ActiveQuery
     */
    public function getProductRelations() : ActiveQuery {
        return $this->hasMany(BoxesProduct::class,['box' => 'id']);
    }
}
