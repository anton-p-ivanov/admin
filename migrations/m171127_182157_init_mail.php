<?php

use yii\db\Migration;

/**
 * Class m171127_182157_init_mail
 */
class m171127_182157_init_mail extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%mail_templates}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(100) not null',
            'active' => 'boolean default "1"',
            'from' => 'varchar(255) not null',
            'to' => 'varchar(255) not null',
            'reply_to' => 'varchar(255) not null',
            'copy' => 'varchar(255) not null',
            'subject' => 'varchar(255) not null',
            'text' => 'text not null',
            'html' => 'text not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%mail_types}}', [
            'uuid' => 'char(36) not null',
            'code' => 'varchar(100) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%mail_templates_sites}}', [
            'template_uuid' => 'char(36) not null',
            'site_uuid' => 'char(36) not null',
            'PRIMARY KEY (`template_uuid`, `site_uuid`)',
            'CONSTRAINT FOREIGN KEY (`template_uuid`) REFERENCES {{%mail_templates}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`site_uuid`) REFERENCES {{%sites}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%mail_templates_types}}', [
            'template_uuid' => 'char(36) not null',
            'type_uuid' => 'char(36) not null',
            'PRIMARY KEY (`template_uuid`, `type_uuid`)',
            'CONSTRAINT FOREIGN KEY (`template_uuid`) REFERENCES {{%mail_templates}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%mail_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%forms_events}}', [
            'form_uuid' => 'char(36) not null',
            'type_uuid' => 'char(36) not null',
            'PRIMARY KEY (`form_uuid`, `type_uuid`)',
            'CONSTRAINT FOREIGN KEY (`form_uuid`) REFERENCES {{%forms}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`type_uuid`) REFERENCES {{%mail_types}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->addColumn('{{%forms}}', 'mail_template_uuid', 'char(36) null default null');
        $this->addForeignKey('{{%forms_fk_mail_template_uuid}}', '{{%forms}}', 'mail_template_uuid', '{{%mail_templates}}', 'uuid', 'SET NULL', 'CASCADE');

        $this->addColumn('{{%forms_statuses}}', 'mail_template_uuid', 'char(36) null default null');
        $this->addForeignKey('{{%forms_statuses_fk_mail_template_uuid}}', '{{%forms_statuses}}', 'mail_template_uuid', '{{%mail_templates}}', 'uuid', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%forms_statuses_fk_mail_template_uuid}}', '{{%forms_statuses}}');
        $this->dropForeignKey('{{%forms_fk_mail_template_uuid}}', '{{%forms}}');

        $this->dropColumn('{{%forms_statuses}}', 'mail_template_uuid');
        $this->dropColumn('{{%forms}}', 'mail_template_uuid');

        $this->dropTable('{{%forms_events}}');
        $this->dropTable('{{%mail_templates_types}}');
        $this->dropTable('{{%mail_templates_sites}}');
        $this->dropTable('{{%mail_types}}');
        $this->dropTable('{{%mail_templates}}');
    }
}
