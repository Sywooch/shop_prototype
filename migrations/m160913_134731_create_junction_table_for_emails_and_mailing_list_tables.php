<?php

use yii\db\Migration;

/**
 * Handles the creation for table `emails_mailing_list`.
 * Has foreign keys to the tables:
 *
 * - `emails`
 * - `mailing_list`
 */
class m160913_134731_create_junction_table_for_emails_and_mailing_list_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('emails_mailing_list', [
            'id_email' => $this->integer(5)->unsigned()->notNull(),
            'id_mailing_list' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('emails_mailing_list_id_email_id_mailing_list', 'emails_mailing_list', ['id_email', 'id_mailing_list'], true);
        
        $this->addForeignKey('emails_mailing_list_id_email', 'emails_mailing_list', 'id_email', 'emails', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('emails_mailing_list_id_mailing_list', 'emails_mailing_list', 'id_mailing_list', 'mailing_list', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('emails_mailing_list_id_mailing_list', 'emails_mailing_list');

        $this->dropForeignKey('emails_mailing_list_id_email', 'emails_mailing_list');
        
        $this->dropIndex('emails_mailing_list_id_email_id_mailing_list', 'emails_mailing_list');

        $this->dropTable('emails_mailing_list');
    }
}
