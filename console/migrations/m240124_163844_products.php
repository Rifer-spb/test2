<?php

use yii\db\Migration;

/**
 * Class m240124_163844_products
 */
class m240124_163844_products extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'; //utf8_unicode_ci
        }
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название'),
            'sku' => $this->string()->unique()->comment('SKU'),
            'shipped_qty' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Shipped Qty'),
            'received_qty' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Received Qty'),
            'price' => $this->float()->unsigned()->notNull()->defaultValue(0)->comment('Price')
        ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%products}}');
    }
}
