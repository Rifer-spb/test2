<?php

use yii\db\Migration;

/**
 * Class m240124_163535_boxes_status
 */
class m240124_163535_boxes_status extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'; //utf8_unicode_ci
        }
        $this->createTable('{{%boxes_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Название')
        ], $tableOptions);

        $this->insert('{{%boxes_status}}', ['name' => 'Expected']);
        $this->insert('{{%boxes_status}}', ['name' => 'At warehouse']);
        $this->insert('{{%boxes_status}}', ['name' => 'Prepared']);
        $this->insert('{{%boxes_status}}', ['name' => 'Shipped']);
    }

    public function down() {
        $this->dropTable('{{%boxes_status}}');
    }
}
