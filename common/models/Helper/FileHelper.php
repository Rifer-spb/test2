<?php

namespace common\models\Helper;

class FileHelper
{
    /**
     * @param $dir
     * @param $rule
     * Создание директории если она не существует
     */
    public static function createFolder($dir,$rule) {
        if(!is_dir($dir)) {
            mkdir($dir, $rule, true);
        }
    }
}