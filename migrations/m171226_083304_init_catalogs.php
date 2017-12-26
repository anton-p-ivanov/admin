<?php

use yii\db\Migration;

/**
 * Class m171226_083304_init_catalogs
 */
class m171226_083304_init_catalogs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%catalogs_types}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(255) not null',
            'sort' => 'integer(10) unsigned not null default \'100\'',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%catalogs_types_i18n}}', [
            'type_uuid' => 'char(36) not null',
            'lang' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'PRIMARY KEY (`type_uuid`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%catalogs_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%catalogs}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(255) not null',
            'sort' => 'integer(10) unsigned not null default \'100\'',
            'active' => 'tinyint(1) unsigned not null default \'1\'',
            'trade' => 'tinyint(1) unsigned not null default \'0\'',
            'index' => 'tinyint(1) unsigned not null default \'0\'',
            'type_uuid' => 'char(36) null default null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%catalogs_types}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%catalogs_i18n}}', [
            'catalog_uuid' => 'char(36) not null',
            'lang' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'PRIMARY KEY (`catalog_uuid`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`catalog_uuid`) REFERENCES {{%catalogs}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%catalogs_i18n}}');
        $this->dropTable('{{%catalogs}}');
        $this->dropTable('{{%catalogs_types_i18n}}');
        $this->dropTable('{{%catalogs_types}}');
    }
}
