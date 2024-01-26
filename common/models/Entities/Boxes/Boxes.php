<?php

namespace common\models\Entities\Boxes;

use yii\db\ActiveQuery;
use common\models\Entities\Products\Products;
use common\models\Entities\Behaviors\SaveRelationsBehavior;
use yii\db\StaleObjectException;

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
 * @property BoxesStatus $statusRelation
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
     */
    public function removeProduct(int $id) {
        $productRelations = $this->productRelations;
        foreach ($productRelations as $key=>$productRelation) {
            if($productRelation->product == $id) {
                unset($productRelations[$key]);
                $this->productRelations = $productRelations;
                return;
            }
        }
        throw new \DomainException('Product not found');
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
            ->andWhere('(SELECT pp.received_qty FROM ' . Products::tableName() . ' pp WHERE pp.id=p.id)!=p.shipped_qty')
            ->exists();
    }

    /**
     * @return false|string
     */
    public function getDateCreatedString() {
        return date('d.m.Y', $this->date_created);
    }

    /**
     * @return BoxesStatus
     */
    public function getStatus() {
        if(is_null($this->statusRelation)) {
            throw new \DomainException('Status not found');
        }
        return $this->statusRelation;
    }

    /**
     * @return float|int
     */
    public function countVolume() {
        $width = $this->width*100;
        $height = $this->height*100;
        $length = $this->length*100;
        return $width*$height*$length;
    }

    /**
     * @return mixed
     */
    public function countPrice() {
        return Products::find()->where([
            'id' => $this->getProductRelations()->select('product')->column()
        ])->sum('price');
    }

    /**
     * @return ActiveQuery
     */
    public function getProductRelations() : ActiveQuery {
        return $this->hasMany(BoxesProduct::class,['box' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatusRelation() : ActiveQuery {
        return $this->hasOne(BoxesStatus::class, ['id' => 'status']);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function afterDelete() {
        parent::afterDelete(); // TODO: Change the autogenerated stub
        foreach ($this->productRelations as $productRelation) {
            if(!is_null($productRelation->productRelation)) {
                $productRelation->productRelation->delete();
            }
            $productRelation->delete();
        }
    }
}
