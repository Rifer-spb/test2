<?php

namespace common\models\Entities\Boxes;

use common\models\Entities\Products\Products;
use yii\db\ActiveQuery;
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
     * @return ActiveQuery
     */
    public function getProductRelations() : ActiveQuery {
        return $this->hasMany(BoxesProduct::class,['box' => 'id']);
    }
}
