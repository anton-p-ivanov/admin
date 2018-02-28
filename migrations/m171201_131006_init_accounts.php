<?php

use yii\db\Migration;

/**
 * Class m171201_131006_init_accounts
 */
class m171201_131006_init_accounts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%accounts}}', [
            'uuid' => 'char(36) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'details' => 'text not null',
            'email' => 'varchar(255) not null default ""',
            'web' => 'varchar(255) not null default ""',
            'phone' => 'varchar(50) not null default ""',
            'active' => 'tinyint(1) not null default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'parent_uuid' => 'char(36) null default null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`parent_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/accounts.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/accounts.sql'));
        }

        $this->createTable('{{%users_accounts}}', [
            'uuid' => 'char(36) not null',
            'user_uuid' => 'char(36) not null',
            'account_uuid' => 'char(36) not null',
            'position' => 'varchar(255) not null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `user_account` (`user_uuid`, `account_uuid`)',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_codes}}', [
            'account_uuid' => 'char(36) not null',
            'code' => 'char(60) not null',
            'valid' => 'boolean not null default \'1\'',
            'issue_date' => 'timestamp null default null',
            'valid_date' => 'timestamp null default null',
            'PRIMARY KEY (`account_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_contacts}}', [
            'uuid' => 'char(36) not null',
            'account_uuid' => 'char(36) not null',
            'user_uuid' => 'char(36) null default null',
            'email' => 'varchar(255) not null',
            'fullname' => 'varchar(255) not null',
            'position' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_managers}}', [
            'uuid' => 'char(36) not null',
            'account_uuid' => 'char(36) not null',
            'manager_uuid' => 'char(36) not null',
            'comments' => 'text not null',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `account_manager` (`account_uuid`, `manager_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`manager_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_sites}}', [
            'account_uuid' => 'char(36) not null',
            'site_uuid' => 'char(36) not null',
            'PRIMARY KEY (`account_uuid`, `site_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`site_uuid`) REFERENCES {{%sites}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_types}}', [
            'uuid' => 'char(36) not null',
            'sort' => 'int(10) unsigned not null default \'100\'',
            'default' => 'boolean not null default \'1\'',
            'PRIMARY KEY (`uuid`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_types_i18n}}', [
            'type_uuid' => 'char(36) not null',
            'lang' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'PRIMARY KEY (`type_uuid`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%accounts_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/accounts_types.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/accounts_types.sql'));
        }

        $this->createTable('{{%accounts_types_assignments}}', [
            'account_uuid' => 'char(36) not null',
            'type_uuid' => 'char(36) not null',
            'PRIMARY KEY (`account_uuid`, `type_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%accounts_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        $this->createTable('{{%accounts_addresses}}', [
            'account_uuid' => 'char(36) not null',
            'address_uuid' => 'char(36) not null',
            'PRIMARY KEY (`account_uuid`, `address_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`address_uuid`) REFERENCES {{%addresses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        $this->createTable('{{%accounts_fields}}', [
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
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_fields_validators}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'type' => 'char(1) not null',
            'options' => 'text',
            'active' => 'boolean default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%accounts_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_fields_values}}', [
            'uuid' => 'char(36) not null',
            'field_uuid' => 'char(36) not null',
            'value' => 'varchar(255) not null',
            'label' => 'varchar(255) not null',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`field_uuid`) REFERENCES {{%accounts_fields}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%accounts_data}}', [
            'account_uuid' => 'char(36) not null',
            'data' => 'text not null',
            'PRIMARY KEY (`account_uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%accounts_data}}');
        $this->dropTable('{{%accounts_fields_values}}');
        $this->dropTable('{{%accounts_fields_validators}}');
        $this->dropTable('{{%accounts_fields}}');
        $this->dropTable('{{%accounts_addresses}}');
        $this->dropTable('{{%accounts_types_assignments}}');
        $this->dropTable('{{%accounts_types_i18n}}');
        $this->dropTable('{{%accounts_types}}');
        $this->dropTable('{{%accounts_sites}}');
        $this->dropTable('{{%accounts_managers}}');
        $this->dropTable('{{%accounts_contacts}}');
        $this->dropTable('{{%accounts_codes}}');
        $this->dropTable('{{%users_accounts}}');
        $this->dropTable('{{%accounts}}');
    }
}
