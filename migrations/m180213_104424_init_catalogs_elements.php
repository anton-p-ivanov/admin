<?php

use yii\db\Migration;

/**
 * Class m180213_104424_init_catalogs_elements
 */
class m180213_104424_init_catalogs_elements extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%catalogs_elements}}', [
            'uuid' => 'char(36) not null',
            'type' => 'char(1) not null default "E"',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'content' => 'longtext not null',
            'active' => 'tinyint(1) unsigned not null default "1"',
            'active_from_date' => 'timestamp null default null',
            'active_to_date' => 'timestamp null default null',
            'sort' => 'int(10) unsigned not null default "100"',
            'code' => 'varchar(255) not null',
            'catalog_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
            'CONSTRAINT FOREIGN KEY (`catalog_uuid`) REFERENCES {{%catalogs}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%catalogs_elements_search}}', [
            'id' => 'int(10) unsigned not null auto_increment',
            'element_uuid' => 'char(36) not null',
            'catalog_uuid' => 'char(36) not null',
            'title' => 'text not null',
            'description' => 'text not null',
            'content' => 'longtext not null',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `element_uuid` (`element_uuid`)',
            'CONSTRAINT FOREIGN KEY (`element_uuid`) REFERENCES {{%catalogs_elements}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%catalogs_elements_sites}}', [
            'element_uuid' => 'char(36) not null',
            'site_uuid' => 'char(36) not null',
            'PRIMARY KEY (`element_uuid`, `site_uuid`)',
            'CONSTRAINT FOREIGN KEY (`element_uuid`) REFERENCES {{%catalogs_elements}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`site_uuid`) REFERENCES {{%sites}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        $this->createTable('{{%catalogs_elements_tree}}', [
            'id' => 'int(10) unsigned auto_increment',
            'tree_uuid' => 'char(36) null default null',
            'element_uuid' => 'char(36) null default null',
            'root' => 'int(10) unsigned null default null',
            'left' => 'int(10) unsigned not null',
            'right' => 'int(10) unsigned not null',
            'level' => 'int(10) unsigned not null',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `tree_uuid` (`tree_uuid`)',
            'CONSTRAINT FOREIGN KEY (`element_uuid`) REFERENCES {{%catalogs_elements}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        Yii::setAlias('@catalogs', '@app/modules/catalogs');
        Yii::setAlias('@i18n', '@app/modules/i18n');
        $schema = \catalogs\models\Catalog::getTableSchema();

        $this->addForeignKey($schema->name . '_ibfk_tree', '{{%catalogs}}', 'tree_uuid', '{{%catalogs_elements_tree}}', 'tree_uuid', 'SET NULL', 'CASCADE');

        $this->createTable('{{%catalogs_elements_fields}}', [
            'element_uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'text not null',
            'value_bool' => 'tinyint(1) unsigned null default null',
            'value_int' => 'int(10) null default null',
            'value_float' => 'decimal(18,4) null default null',
            'PRIMARY KEY (`element_uuid`, `field_uuid`)',
            'INDEX `ix_value_bool` (value_bool, element_uuid)',
            'INDEX `ix_value_int` (value_int, element_uuid)',
            'INDEX `ix_value_float` (value_float, element_uuid)',
            'CONSTRAINT FOREIGN KEY (`element_uuid`) REFERENCES {{%catalogs_elements}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%catalogs_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%catalogs_elements_fields}}');

        Yii::setAlias('@catalogs', '@app/modules/catalogs');
        Yii::setAlias('@i18n', '@app/modules/i18n');
        $schema = \catalogs\models\Catalog::getTableSchema();

        $this->dropForeignKey($schema->name . '_ibfk_tree', '{{%catalogs}}');
        $this->dropTable('{{%catalogs_elements_tree}}');
        $this->dropTable('{{%catalogs_elements_sites}}');
        $this->dropTable('{{%catalogs_elements_search}}');
        $this->dropTable('{{%catalogs_elements}}');
    }
}
