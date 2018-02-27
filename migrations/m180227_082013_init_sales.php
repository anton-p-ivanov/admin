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

        if (file_exists(__DIR__ . '/sql/sales_discounts.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/sales_discounts.sql'));
        }

        $this->createTable('{{%sales_discounts_accounts}}', [
            'uuid' => 'char(36) not null',
            'status_uuid' => 'char(36) not null',
            'discount_uuid' => 'char(36) not null',
            'value' => 'decimal(5,4)',
            'issue_date' => 'timestamp null default null',
            'expire_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%accounts_statuses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`discount_uuid`) REFERENCES {{%sales_discounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%sales_discounts_statuses}}', [
            'uuid' => 'char(36) not null',
            'status_uuid' => 'char(36) not null',
            'discount_uuid' => 'char(36) not null',
            'value' => 'decimal(5,4)',
            'issue_date' => 'timestamp null default null',
            'expire_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%partnership_statuses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`discount_uuid`) REFERENCES {{%sales_discounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sales_discounts_statuses}}');
        $this->dropTable('{{%sales_discounts_accounts}}');
        $this->dropTable('{{%sales_discounts}}');
    }
}
