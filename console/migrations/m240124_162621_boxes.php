<?php

use yii\db\Migration;

/**
 * Class m240124_162621_boxes
 */
class m240124_162621_boxes extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'; //utf8_unicode_ci
        }
        $this->createTable('{{%boxes}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название'),
            'date_created' => $this->integer()->unsigned()->notNull()->comment('Дата создания'),
            'weight' => $this->float()->unsigned()->notNull()->defaultValue(0)->comment('Вес'),
            'width' => $this->float()->unsigned()->notNull()->defaultValue(0)->comment('Ширина'),
            'length' => $this->float()->unsigned()->notNull()->defaultValue(0)->comment('Длина'),
            'height' => $this->float()->unsigned()->notNull()->defaultValue(0)->comment('Высота'),
            'reference' => $this->string()->notNull()->comment('Инфо'),
            'status' => $this->integer()->unsigned()->notNull()->comment('Статус')
        ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%boxes}}');
    }
}
