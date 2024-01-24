<?php

namespace common\models\Entities\Boxes;

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
}
