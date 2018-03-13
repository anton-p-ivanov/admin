<?php

use yii\db\Migration;

class m171004_132506_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%i18n_languages}}', [
            'code' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'default' => 'tinyint(1) unsigned not null default \'0\'',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'PRIMARY KEY (`code`)',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/i18n_languages.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/i18n_languages.sql'));
        }

        $this->createTable('{{%auth_rules}}', [
            'name' => 'varchar(64) not null',
            'data' => 'text',
            'created_at' => 'timestamp null default null',
            'updated_at' => 'timestamp null default null',
            'PRIMARY KEY (`name`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%auth_items}}', [
            'name' => 'varchar(64) not null',
            'type' => 'tinyint(1) not null',
            'description' => 'text null default null',
            'rule_name' => 'varchar(64)',
            'data' => 'text',
            'created_at' => 'timestamp null default null',
            'updated_at' => 'timestamp null default null',
            'PRIMARY KEY (`name`)',
            'KEY `type` (`type`)',
            'CONSTRAINT FOREIGN KEY (`rule_name`) REFERENCES {{%auth_rules}} (`name`) ON DELETE SET NULL ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%auth_items_children}}', [
            'parent' => 'varchar(64) not null',
            'child' => 'varchar(64) not null',
            'PRIMARY KEY (`parent`, `child`)',
            'CONSTRAINT FOREIGN KEY (`parent`) REFERENCES {{%auth_items}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`child`) REFERENCES {{%auth_items}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%auth_assignments}}', [
            'uuid' => 'char(36) not null',
            'item_name' => 'varchar(64) not null',
            'user_id' => 'char(36) not null',
            'created_at' => 'timestamp null default null',
            'valid_from_date' => 'timestamp null default null',
            'valid_to_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `user_item` (`item_name`, `user_id`)',
            'CONSTRAINT FOREIGN KEY (`item_name`) REFERENCES {{%auth_items}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%auth_items_i18n}}', [
            'item_name' => 'varchar(64) not null',
            'lang' => 'varchar(10) not null',
            'description' => 'text',
            'PRIMARY KEY (`item_name`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`item_name`) REFERENCES {{%auth_items}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (!YII_ENV_TEST) {
            $this->execute(file_get_contents(__DIR__ . "/sql/auth_items.sql"));
        }

        $this->createTable('{{%sites}}', [
            'uuid' => 'char(36) not null',
            'active' => 'tinyint(1) not null default "1"',
            'title' => 'varchar(255) not null',
            'url' => 'varchar(255) not null',
            'email' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default "100"',
            'code' => 'varchar(50) not null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)'
        ]);

        if (!YII_ENV_TEST && file_exists(__DIR__ . "/sql/sites.sql")) {
            $this->execute(file_get_contents(__DIR__ . "/sql/sites.sql"));
        }

        $this->createTable('{{%users}}', [
            'uuid' => 'char(36) not null',
            'email' => 'varchar(100) not null',
            'fname' => 'varchar(100) not null',
            'lname' => 'varchar(100) not null',
            'sname' => 'varchar(100) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `email` (`email`)',
        ], 'ENGINE InnoDB');

        $this->insert('{{%users}}', [
            'uuid' => new \yii\db\Expression('UUID()'),
            'email' => 'guest.user@example.com',
            'fname' => 'Guest',
            'lname' => 'User'
        ]);

        $this->createTable('{{%users_passwords}}', [
            'user_uuid' => 'char(36) not null',
            'password' => 'char(60) not null',
            'salt' => 'char(20) not null',
            'created_date' => 'timestamp null default null',
            'expired_date' => 'timestamp null default null',
            'expired' => 'boolean not null default \'0\'',
            'PRIMARY KEY (`user_uuid`, `password`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE'
        ]);

        $this->createTable('{{%users_checkwords}}', [
            'user_uuid' => 'char(36) not null',
            'checkword' => 'char(60) not null',
            'PRIMARY KEY (`user_uuid`)',
            'UNIQUE KEY `checkword` (`checkword`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_fields}}', [
            'uuid' => 'char(36) not null',
            'label' => 'varchar(255) not null',
            'description' => 'text not null default \'\'',
            'code' => 'varchar(255) not null',
            'type' => 'tinyint(1) unsigned not null default \'1\'',
            'multiple' => 'boolean not null default \'1\'',
            'default' => 'varchar(255) not null',
            'options' => 'text not null default \'\'',
            'active' => 'boolean not null default \'1\'',
            'list' => 'boolean default \'0\'',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_fields_validators}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'type' => 'char(1) not null',
            'options' => 'text',
            'active' => 'boolean default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%users_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_fields_values}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'varchar(255) not null',
            'label' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%users_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_properties}}', [
            'user_uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'text not null',
            'PRIMARY KEY (`user_uuid`, `field_uuid`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%users_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_settings}}', [
            'user_uuid' => 'char(36) not null',
            'module' => 'varchar(50) not null',
            'name' => 'varchar(50) not null',
            'value' => 'text',
            'PRIMARY KEY (`user_uuid`, `module`, `name`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%users_sites}}', [
            'uuid' => 'char(36) not null',
            'user_uuid' => 'char(36) not null',
            'site_uuid' => 'char(36) not null',
            'active' => 'tinyint(1) unsigned not null default \'1\'',
            'active_from_date' => 'timestamp null default null',
            'active_to_date' => 'timestamp null default null',
            'login_date' => 'timestamp null default null',
            'activity_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `user_site` (`user_uuid`, `site_uuid`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`site_uuid`) REFERENCES {{%sites}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%workflow_statuses}}', [
            'code' => 'char(1) not null',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'PRIMARY KEY (`code`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%workflow_statuses_i18n}}', [
            'code' => 'char(1) not null',
            'lang' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'PRIMARY KEY (`code`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`code`) REFERENCES {{%workflow_statuses}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/workflow_statuses.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/workflow_statuses.sql'));
        }

        $this->createTable('{{%workflow}}', [
            'uuid' => 'char(36) not null',
            'status' => 'char(1) not null',
            'created_by' => 'char(36) null default null',
            'modified_by' => 'char(36) null default null',
            'created_date' => 'timestamp null default null',
            'modified_date' => 'timestamp null default null',
            'removed' => 'boolean not null default \'0\'',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`status`) REFERENCES {{%workflow_statuses}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`modified_by`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE'
        ], 'ENGINE InnoDB');

        $this->addForeignKey('{{%users_ibfk_1}}', '{{%users}}', 'workflow_uuid', '{{%workflow}}', 'uuid', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%users_fields_ibfk_1}}', '{{%users_fields}}', 'workflow_uuid', '{{%workflow}}', 'uuid', 'SET NULL', 'CASCADE');

        $this->createTable('{{%filters}}', [
            'uuid' => 'char(36) not null',
            'query' => 'text',
            'hash' => 'char(32) not null',
            'created_at' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
        ], 'ENGINE InnoDB');
    }

    public function safeDown()
    {

        $this->dropTable('{{%filters}}');

        $this->dropForeignKey('{{%users_fields_ibfk_1}}', '{{%users_fields}}');
        $this->dropForeignKey('{{%users_ibfk_1}}', '{{%users}}');

        $this->dropTable('{{%workflow}}');
        $this->dropTable('{{%workflow_statuses_i18n}}');
        $this->dropTable('{{%workflow_statuses}}');

        $this->dropTable('{{%users_sites}}');
        $this->dropTable('{{%users_settings}}');
        $this->dropTable('{{%users_properties}}');
        $this->dropTable('{{%users_fields_values}}');
        $this->dropTable('{{%users_fields_validators}}');
        $this->dropTable('{{%users_fields}}');
        $this->dropTable('{{%users_checkwords}}');
        $this->dropTable('{{%users_passwords}}');
        $this->dropTable('{{%users}}');

        $this->dropTable('{{%sites}}');

        $this->dropTable('{{%auth_assignments}}');
        $this->dropTable('{{%auth_items_children}}');
        $this->dropTable('{{%auth_items_i18n}}');
        $this->dropTable('{{%auth_items}}');
        $this->dropTable('{{%auth_rules}}');

        $this->dropTable('{{%i18n_languages}}');
    }
}
