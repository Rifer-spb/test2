<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;

class ChangeWeightForm extends Model
{
    public $value;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'number']
        ];
    }
}