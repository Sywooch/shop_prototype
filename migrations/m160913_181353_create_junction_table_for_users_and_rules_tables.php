<?php

use yii\db\Migration;

/**
 * Handles the creation for table `users_rules`.
 * Has foreign keys to the tables:
 *
 * - `users`
 * - `rules`
 */
class m160913_181353_create_junction_table_for_users_and_rules_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('users_rules', [
            'id_user' => $this->integer(5)->unsigned()->notNull(),
            'id_rule' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('users_rules_id_user_id_rule', 'users_rules', ['id_user', 'id_rule'], true);
        
        $this->addForeignKey('users_rules_id_user', 'users_rules', 'id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('users_rules_id_rule', 'users_rules', 'id_rule', 'rules', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('users_rules_id_rule', 'users_rules');
        
        $this->dropForeignKey('users_rules_id_user', 'users_rules');
        
        $this->dropIndex('users_rules_id_user_id_rule', 'users_rules');
        
        $this->dropTable('users_rules');
    }
}
