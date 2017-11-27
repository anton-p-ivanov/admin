<?php

use yii\db\Migration;

class m171005_062739_init_storage extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%storage}}', [
            'uuid' => 'char(36) not null',
            'type' => 'char(1) not null default \'F\'',
            'title' => 'varchar(255) not null',
            'description' => 'text not null default \'\'',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE'
        ]);

        $this->createTable('{{%storage_tree}}', [
            'id' => 'int(10) unsigned auto_increment',
            'tree_uuid' => 'char(36) not null',
            'storage_uuid' => 'char(36) null default null',
            'root' => 'int(10) unsigned null default null',
            'left' => 'int(10) unsigned not null',
            'right' => 'int(10) unsigned not null',
            'level' => 'int(10) unsigned not null',
            'PRIMARY KEY (`id`)',
            'CONSTRAINT FOREIGN KEY (`storage_uuid`) REFERENCES {{%storage}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%storage_files}}', [
            'uuid' => 'char(36) not null',
            'name' => 'varchar(255) not null',
            'size' => 'int(10) unsigned not null',
            'type' => 'varchar(255) not null',
            'hash' => 'char(32) not null',
            'PRIMARY KEY (`uuid`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%storage_images}}', [
            'file_uuid' => 'char(36) not null',
            'width' => 'int(10) unsigned not null',
            'height' => 'int(10) unsigned not null',
            'PRIMARY KEY (`file_uuid`)',
            'CONSTRAINT FOREIGN KEY (`file_uuid`) REFERENCES {{%storage_files}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%storage_versions}}', [
            'storage_uuid' => 'char(36) not null',
            'file_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'active' => 'boolean not null default \'1\'',
            'PRIMARY KEY (`storage_uuid`, `file_uuid`)',
            'CONSTRAINT FOREIGN KEY (`storage_uuid`) REFERENCES {{%storage}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`file_uuid`) REFERENCES {{%storage_files}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%storage_stats}}', [
            'uuid' => 'char(36) not null',
            'file_uuid' => 'char(36) not null',
            'accessed_by' => 'char(36) null default null',
            'accessed_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`file_uuid`) REFERENCES {{%storage_files}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`accessed_by`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('{{%storage_stats}}');
//        $this->dropTable('{{%storage_requests}}');
//        $this->dropTable('{{%storage_roles}}');
        $this->dropTable('{{%storage_versions}}');
        $this->dropTable('{{%storage_images}}');
        $this->dropTable('{{%storage_files}}');
        $this->dropTable('{{%storage_tree}}');
        $this->dropTable('{{%storage}}');
    }
}
