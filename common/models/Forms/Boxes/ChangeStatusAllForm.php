<?php

namespace common\models\Forms\Boxes;

use yii\base\Model;

class ChangeStatusAllForm extends Model
{
    public $items;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['items','status'], 'required'],
            [['status'],'integer'],
            ['items', 'each', 'rule' => ['integer']],
        ];
    }
}