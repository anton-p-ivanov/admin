<?php

use yii\db\Migration;

/**
 * Class m171201_131005_init_addresses
 */
class m171201_131005_init_addresses extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%addresses_countries}}', [
            'code' => 'char(2) not null',
            'title' => 'varchar(255) not null',
            'title_ru-RU' => 'varchar(255) not null',
            'PRIMARY KEY (`code`)'
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/addresses_countries.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/addresses_countries.sql'));
        }

        $this->createTable('{{%addresses_types}}', [
            'uuid' => 'char(36) not null',
            'title' => 'varchar(255) not null',
            'title_ru-RU' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'default' => 'tinyint(1) unsigned not null default \'0\'',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/addresses_types.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/addresses_types.sql'));
        }

        $this->createTable('{{%addresses}}', [
            'uuid' => 'char(36) not null',
            'type_uuid' => 'char(36) not null',
            'country_code' => 'char(2) null default null',
            'region' => 'varchar(255) not null',
            'district' => 'varchar(255) not null',
            'city' => 'varchar(255) not null',
            'zip' => 'varchar(50) not null',
            'address' => 'varchar(255) not null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%addresses_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`country_code`) REFERENCES {{%addresses_countries}} (`code`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createIndex('city', '{{%addresses}}', 'city');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%addresses}}');
        $this->dropTable('{{%addresses_types}}');
        $this->dropTable('{{%addresses_countries}}');
    }
}
