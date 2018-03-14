<?php

use yii\db\Migration;

/**
 * Class m171114_064319_init_forms
 */
class m171114_064319_init_forms extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%forms}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(100) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text',
            'template' => 'text',
            'template_active' => 'boolean default "0"',
            'active' => 'boolean default "1"',
            'active_from_date' => 'timestamp null default null',
            'active_to_date' => 'timestamp null default null',
            'sort' => 'int(10) unsigned not null default "100"',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_statuses}}', [
            'uuid' => 'char(36) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text',
            'active' => 'boolean default "1"',
            'default' => 'boolean default "0"',
            'sort' => 'int(10) unsigned not null default "100"',
            'form_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`form_uuid`) REFERENCES {{%forms}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_results}}', [
            'uuid' => 'char(36) not null',
            'form_uuid' => 'char(36) not null',
            'status_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`form_uuid`) REFERENCES {{%forms}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%forms_statuses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_fields}}', [
            'uuid' => 'char(36) not null',
            'label' => 'varchar(255) not null',
            'description' => 'text',
            'code' => 'varchar(255) not null',
            'type' => 'char(1) not null',
            'multiple' => 'boolean default "0"',
            'default' => 'varchar(255) not null',
            'options' => 'text',
            'active' => 'boolean default "1"',
            'list' => 'boolean default "0"',
            'sort' => 'int(10) unsigned not null default "100"',
            'form_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`form_uuid`) REFERENCES {{%forms}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_fields_validators}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'type' => 'char(1) not null',
            'options' => 'text',
            'active' => 'boolean default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%forms_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_fields_values}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'varchar(255) not null',
            'label' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%forms_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_results_properties}}', [
            'result_uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'text not null',
            'PRIMARY KEY (`result_uuid`, `field_uuid`)',
            'CONSTRAINT FOREIGN KEY (`result_uuid`) REFERENCES {{%forms_results}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%forms_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%forms_results_properties}}');
        $this->dropTable('{{%forms_fields_values}}');
        $this->dropTable('{{%forms_fields_validators}}');
        $this->dropTable('{{%forms_fields}}');
        $this->dropTable('{{%forms_results}}');
        $this->dropTable('{{%forms_statuses}}');
        $this->dropTable('{{%forms}}');
    }
}
