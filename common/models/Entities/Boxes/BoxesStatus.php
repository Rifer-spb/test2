<?php

namespace common\models\Entities\Boxes;

use Yii;

/**
 * This is the model class for table "{{%boxes_status}}".
 *
 * @property int $id
 * @property string $name Название
 */
class BoxesStatus extends \yii\db\ActiveRecord
{
    const AT_WAREHOUSE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%boxes_status}}';
    }
}
