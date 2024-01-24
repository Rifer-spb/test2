<?php

namespace common\models\Repositories;

use common\models\Entities\Boxes\Boxes;

class BoxesRepository
{
    /**
     * @param Boxes $model
     */
    public function save(Boxes $model) {
        if(!$model->save()) {
            throw new \DomainException('Save error');
        }
    }

    /**
     * @param Boxes $model
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(Boxes $model) {
        if(!$model->delete()) {
            throw new \DomainException('Delete error');
        }
    }

    /**
     * @param int $id
     * @return Boxes|null
     */
    public function get(int $id) {
        if(!$model = Boxes::findOne($id)) {
            throw new \DomainException('Box not found');
        }
        return $model;
    }
}