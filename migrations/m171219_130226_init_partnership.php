<?php

use yii\db\Migration;

/**
 * Class m171219_130226_init_partnership
 */
class m171219_130226_init_partnership extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%partnership_statuses}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(255) not null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%partnership_statuses_i18n}}', [
            'status_uuid' => 'char(36) not null',
            'lang' => 'varchar(10) not null',
            'title' => 'varchar(255) not null',
            'PRIMARY KEY (`status_uuid`, `lang`)',
            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%partnership_statuses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`lang`) REFERENCES {{%i18n_languages}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        if (file_exists(__DIR__ . '/sql/partnership_statuses.sql')) {
            $this->execute(file_get_contents(__DIR__ . '/sql/partnership_statuses.sql'));
        }

        $this->createTable('{{%accounts_statuses}}', [
            'uuid' => 'char(36) not null',
            'account_uuid' => 'char(36) not null',
            'status_uuid' => 'char(36) not null',
            'issue_date' => 'datetime null default null',
            'expire_date' => 'datetime null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%partnership_statuses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%accounts_statuses}}');
        $this->dropTable('{{%partnership_statuses_i18n}}');
        $this->dropTable('{{%partnership_statuses}}');
    }
}
