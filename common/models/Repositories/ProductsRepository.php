<?php

namespace common\models\Repositories;

use common\models\Entities\Products\Products;

class ProductsRepository
{
    /**
     * @param Boxes $model
     */
    public function save(Products $model) {
        if(!$model->save()) {
            throw new \DomainException('Save error');
        }
    }

    /**
     * @param Products $model
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(Products $model) {
        if(!$model->delete()) {
            throw new \DomainException('Delete error');
        }
    }

    /**
     * @param int $id
     * @return Products|null
     */
    public function get(int $id) {
        if(!$model = Products::findOne($id)) {
            throw new \DomainException('Box not found');
        }
        return $model;
    }
}