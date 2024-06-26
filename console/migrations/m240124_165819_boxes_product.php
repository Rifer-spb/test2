<?php

use yii\db\Migration;

/**
 * Class m240124_165819_boxes_product
 */
class m240124_165819_boxes_product extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'; //utf8_unicode_ci
        }
        $this->createTable('{{%boxes_product}}', [
            'id' => $this->primaryKey(),
            'box' => $this->integer()->unsigned()->notNull()->comment('Коробка'),
            'product' => $this->integer()->comment('Продукт')
        ], $tableOptions);

        $this->createIndex('{{%idx-boxes_product-box}}', '{{%boxes_product}}', 'box');
        $this->createIndex('{{%idx-boxes_product-product}}', '{{%boxes_product}}', 'product');

        $this->addForeignKey('{{%fk-boxes_product-product}}', '{{%boxes_product}}', 'product', '{{%products}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function down() {
        $this->dropTable('{{%boxes_product}}');
    }
}
