<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;

class ChangeStatusForm extends Model
{
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer']
        ];
    }
}