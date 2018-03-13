<?php

use yii\db\Migration;

/**
 * Class m180222_100522_init_oauth
 */
class m180222_100522_init_oauth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%oauth_access_tokens}}', [
            'access_token' => 'varchar(40) not null',
            'client_id' => 'varchar(80) not null',
            'user_id' => 'varchar(255) default null',
            'expires' => 'timestamp',
            'scope' => 'varchar(2000) default null',
            'PRIMARY KEY (`access_token`)'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%oauth_authorization_codes}}', [
            'authorization_code' => 'varchar(40) not null',
            'client_id' => 'varchar(80) not null',
            'user_id' => 'varchar(255) default null',
            'redirect_uri' => 'varchar(2000) default null',
            'expires' => 'timestamp',
            'scope' => 'varchar(2000) default null',
            'PRIMARY KEY (`authorization_code`)'
        ], 'ENGINE InnoDB');

        $this->createTable('{{%oauth_clients}}', [
            'client_id' => 'varchar(80) not null',
            'client_secret' => 'varchar(80) not null',
            'redirect_uri' => 'varchar(2000) not null',
            'grant_types' => 'varchar(80) default null',
            'scope' => 'varchar(100) default null',
            'user_id' => 'varchar(80) default null',
            'PRIMARY KEY (`client_id`)'
        ], 'ENGINE InnoDB');

        foreach (Yii::$app->db->createCommand('SELECT * FROM {{%sites}}')->queryAll() as $site) {
            $this->insert('{{%oauth_clients}}', [
                'client_id' => $site['uuid'],
                'client_secret' => Yii::$app->security->generatePasswordHash('client_secret'),
                'redirect_uri' => ''
            ]);
        }

        $this->createTable('{{%oauth_refresh_tokens}}', [
            'refresh_token' => 'varchar(40) not null',
            'client_id' => 'varchar(80) not null',
            'user_id' => 'varchar(255) default null',
            'expires' => 'timestamp',
            'scope' => 'varchar(100) default null',
            'PRIMARY KEY (`refresh_token`)'
        ]);

        $this->createTable('{{%oauth_scopes}}', [
            'scope' => 'text default null',
            'is_default' => 'tinyint(1) default null'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%oauth_scopes}}');
        $this->dropTable('{{%oauth_refresh_tokens}}');
        $this->dropTable('{{%oauth_clients}}');
        $this->dropTable('{{%oauth_authorization_codes}}');
        $this->dropTable('{{%oauth_access_tokens}}');
    }
}
