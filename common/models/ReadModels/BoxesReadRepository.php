<?php

namespace common\models\ReadModels;

use common\models\Entities\Boxes\Boxes;
use yii\db\ActiveRecord;
use common\models\Entities\Boxes\BoxesStatus;

class BoxesReadRepository
{
    /**
     * @param int $id
     * @return Boxes|null
     */
    public function findBox(int $id) : ?Boxes {
        return Boxes::findOne($id);
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function findStatusArray() {
        return BoxesStatus::find()->asArray()->all();
    }
}