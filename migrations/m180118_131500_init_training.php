<?php

use yii\db\Migration;

/**
 * Class m180118_131500_init_training
 */
class m180118_131500_init_training extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%training_courses}}', [
            'uuid' => 'char(36) not null',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'description_format' => 'char(1) not null default "T"',
            'active' => 'tinyint(1) unsigned not null default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'code' => 'varchar(255) not null',
//            'tree_uuid' => 'char(36) null default null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_lessons}}', [
            'uuid' => 'char(36) not null',
//            'type' => 'char(1) not null default "E"',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'description_format' => 'varchar(10) DEFAULT \'HTML\'',
            'active' => 'tinyint(1) unsigned NOT NULL DEFAULT \'1\'',
            'sort' => 'int(10) unsigned NOT NULL DEFAULT \'100\'',
            'code' => 'varchar(255) not null',
            'course_uuid' => 'char(36) not null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'UNIQUE KEY `code` (`code`)',
            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON UPDATE CASCADE ON DELETE SET NULL',
        ], 'ENGINE InnoDB');

//        $this->createTable('{{%training_lessons_tree}}', [
//            'uuid' => 'char(36) not null',
//            'lesson_uuid' => 'char(36) null default null',
//            'tree' => 'char(36) not null',
//            'lft' => 'int(10) unsigned NOT NULL',
//            'rgt' => 'int(10) unsigned NOT NULL',
//            'depth' => 'int(10) unsigned NOT NULL',
//            'PRIMARY KEY (`uuid`)',
//            'KEY `tree` (`tree`)',
//        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_tests}}', [
            'uuid' => 'char(36) not null',
            'course_uuid' => 'char(36) not null',
            'active' => 'tinyint(1) unsigned NOT NULL DEFAULT "1"',
            'title' => 'varchar(255) not null',
            'description' => 'text not null',
            'description_format' => 'varchar(10) DEFAULT "HTML"',
            'questions_random' => 'tinyint(1) unsigned NOT NULL DEFAULT "1"',
            'answers_random' => 'tinyint(1) unsigned NOT NULL DEFAULT "1"',
            'limit_attempts' => 'int(1) unsigned not null',
            'limit_time' => 'int(1) unsigned not null',
            'limit_percent' => 'int(1) unsigned not null',
            'limit_value' => 'int(10) unsigned not null default "0"',
            'limit_questions' => 'int(1) unsigned not null default "0"',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON UPDATE CASCADE ON DELETE SET NULL',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_questions}}', [
            'uuid' => 'char(36) not null',
            'title' => 'varchar(500) not null',
            'description' => 'text not null',
            'description_format' => 'varchar(10) DEFAULT "HTML"',
            'active' => 'tinyint(1) unsigned NOT NULL DEFAULT "1"',
            'type' => 'char(1) not null default "S"',
            'sort' => 'int(10) unsigned NOT NULL DEFAULT "100"',
            'value' => 'int(10) unsigned NOT NULL DEFAULT "10"',
            'lesson_uuid' => 'char(36) not null',
            'CONSTRAINT FOREIGN KEY (`lesson_uuid`) REFERENCES {{%training_lessons}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'PRIMARY KEY (`uuid`)',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_tests_questions}}', [
            'test_uuid' => 'char(36) not null',
            'question_uuid' => 'char(36) not null',
            'PRIMARY KEY (`test_uuid`, `question_uuid`)',
            'CONSTRAINT FOREIGN KEY (`test_uuid`) REFERENCES {{%training_tests}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT FOREIGN KEY (`question_uuid`) REFERENCES {{%training_questions}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_answers}}', [
            'uuid' => 'char(36) not null',
            'question_uuid' => 'char(36) not null',
            'answer' => 'text not null',
            'valid' => 'tinyint(1) unsigned not null default "1"',
            'sort' => 'int(10) unsigned not null default "100"',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`question_uuid`) REFERENCES {{%training_questions}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_attempts}}', [
            'uuid' => 'char(36) not null',
            'test_uuid' => 'char(36) null default null',
            'user_uuid' => 'char(36) null default null',
            'success' => 'tinyint(1) unsigned not null default "1"',
            'begin_date' => 'timestamp null default null',
            'end_date' => 'timestamp null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`test_uuid`) REFERENCES {{%training_tests}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_attempts_data}}', [
            'attempt_uuid' => 'char(36) not null',
            'question_uuid' => 'char(36) NOT NULL',
            'answer_uuid' => 'char(36) NOT NULL',
            'value' => 'int(10) unsigned not null default "0"',
            'PRIMARY KEY (`attempt_uuid`, `question_uuid`, `answer_uuid`)',
            'CONSTRAINT FOREIGN KEY (`attempt_uuid`) REFERENCES {{%training_attempts}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT FOREIGN KEY (`question_uuid`) REFERENCES {{%training_questions}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT FOREIGN KEY (`answer_uuid`) REFERENCES {{%training_answers}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
        ], 'ENGINE InnoDB');
//
//        $this->createTable('{{%training_courses_accounts}}', [
//            'course_uuid' => 'char(36) not null',
//            'account_uuid' => 'char(36) not null',
//            'PRIMARY KEY (`course_uuid`, `account_uuid`)',
//            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//        ], 'ENGINE InnoDB');
//
//        $this->createTable('{{%training_courses_roles}}', [
//            'course_uuid' => 'char(36) not null',
//            'auth_item' => 'varchar(64) NOT NULL',
//            'PRIMARY KEY (`course_uuid`, `auth_item`)',
//            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//            'CONSTRAINT FOREIGN KEY (`auth_item`) REFERENCES {{%auth_items}} (`name`) ON UPDATE CASCADE ON DELETE CASCADE',
//        ], 'ENGINE InnoDB');
//
//        $this->createTable('{{%training_courses_sites}}', [
//            'course_uuid' => 'char(36) not null',
//            'site_uuid' => 'char(36) NOT NULL',
//            'PRIMARY KEY (`course_uuid`, `site_uuid`)',
//            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//            'CONSTRAINT FOREIGN KEY (`site_uuid`) REFERENCES {{%sites}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//        ], 'ENGINE InnoDB');
//
//        $this->createTable('{{%training_courses_statuses}}', [
//            'course_uuid' => 'char(36) not null',
//            'status_uuid' => 'char(36) NOT NULL',
//            'PRIMARY KEY (`course_uuid`, `status_uuid`)',
//            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//            'CONSTRAINT FOREIGN KEY (`status_uuid`) REFERENCES {{%partnership_statuses}} (`uuid`) ON UPDATE CASCADE ON DELETE CASCADE',
//        ], 'ENGINE InnoDB');

        $this->createTable('{{%training_certificates}}', [
            'uuid' => 'char(36) not null',
            'course_uuid' => 'char(36) not null',
            'user_uuid' => 'char(36) null default null',
            'account_uuid' => 'char(36) null default null',
            'name' => 'varchar(255) not null default ""',
            'position' => 'varchar(255) not null default ""',
            'valid' => 'tinyint(1) not null default 1',
            'issue_date' => 'timestamp null default null',
            'valid_date' => 'timestamp null default null',
            'workflow_uuid' => 'char(36) null default null',
            'PRIMARY KEY (`uuid`)',
            'CONSTRAINT FOREIGN KEY (`course_uuid`) REFERENCES {{%training_courses}} (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`user_uuid`) REFERENCES {{%users}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`account_uuid`) REFERENCES {{%accounts}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
            'CONSTRAINT FOREIGN KEY (`workflow_uuid`) REFERENCES {{%workflow}} (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE',
        ], 'ENGINE InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%training_certificates}}');
//        $this->dropTable('{{%training_courses_statuses}}');
//        $this->dropTable('{{%training_courses_sites}}');
//        $this->dropTable('{{%training_courses_roles}}');
//        $this->dropTable('{{%training_courses_accounts}}');
        $this->dropTable('{{%training_attempts_data}}');
        $this->dropTable('{{%training_attempts}}');
        $this->dropTable('{{%training_answers}}');
        $this->dropTable('{{%training_tests_questions}}');
        $this->dropTable('{{%training_questions}}');
        $this->dropTable('{{%training_tests}}');
//        $this->dropTable('{{%training_lessons_tree}}');
        $this->dropTable('{{%training_lessons}}');
        $this->dropTable('{{%training_courses}}');
    }
}
