<?php

use yii\db\Migration;

/**
 * Class m180227_082013_init_sales
 */
class m180227_082013_init_sales extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sales_discounts}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(200) not null',
            'title' => 'varchar(200) not null',
            'value' => 'decimal(5,4)',
            'workflow_uuid' => 'char(36) default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sales_discounts}}');
    }
}
