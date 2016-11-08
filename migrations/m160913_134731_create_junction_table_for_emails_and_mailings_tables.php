<?php

use yii\db\Migration;

/**
 * Handles the creation for table `emails_mailings`.
 * Has foreign keys to the tables:
 *
 * - `emails`
 * - `mailings`
 */
class m160913_134731_create_junction_table_for_emails_and_mailings_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('emails_mailings', [
            'id_email' => $this->integer(5)->unsigned()->notNull(),
            'id_mailing' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('id_email_id_mailing', 'emails_mailings', ['id_email', 'id_mailing'], true);
        
        $this->addForeignKey('id_email', 'emails_mailings', 'id_email', 'emails', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('id_mailing', 'emails_mailings', 'id_mailing', 'mailings', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('id_mailing', 'emails_mailings');

        $this->dropForeignKey('id_email', 'emails_mailings');
        
        $this->dropIndex('id_email_id_mailing', 'emails_mailings');

        $this->dropTable('emails_mailings');
    }
}
