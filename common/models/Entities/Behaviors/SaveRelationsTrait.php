<?php

namespace common\models\Entities\Behaviors;

trait SaveRelationsTrait
{

    public function load($data, $formName = null)
    {
        $loaded = parent::load($data, $formName);
        if ($loaded && $this->hasMethod('loadRelations')) {
            $this->loadRelations($data);
        }
        return $loaded;
    }
}