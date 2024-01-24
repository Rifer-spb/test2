<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;

/**
 * Class AddForm
 * @package common\models\Forms\Boxes
 *
 * @property string $title
 * @property string $reference
 * @property float $weight
 * @property float $width
 * @property float $length
 * @property float $height
 */
class AddForm extends Model
{
    public $title;
    public $reference;
    public $weight = 0;
    public $width = 0;
    public $length = 0;
    public $height = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reference','title'], 'required'],
            [['weight', 'width', 'length', 'height'], 'number'],
            [['reference','title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight' => 'Weight, kg',
            'width' => 'Width, cm',
            'length' => 'Length, cm',
            'height' => 'Height, cm',
            'reference' => 'Reference'
        ];
    }
}