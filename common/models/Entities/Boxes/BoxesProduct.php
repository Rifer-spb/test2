<?php

namespace common\models\Entities\Boxes;

use Yii;

/**
 * This is the model class for table "{{%boxes_product}}".
 *
 * @property int $id
 * @property int $box Коробка
 * @property int $product Продукт
 */
class BoxesProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%boxes_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['box', 'product'], 'required'],
            [['box', 'product'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'box' => 'Box',
            'product' => 'Product',
        ];
    }
}
