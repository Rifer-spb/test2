<?php

namespace common\models\Service;

class TransactionManager
{
    public function begin()
    {
        return new Transaction(\Yii::$app->db->beginTransaction());
    }
} 