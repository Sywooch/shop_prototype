<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comments`.
 */
class m160913_130551_create_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'date'=>$this->integer(10)->unsigned()->notNull(),
            'text'=>$this->string(2000)->notNull(),
            'name'=>$this->string(255)->notNull(),
            'id_email'=>$this->integer(5)->unsigned()->notNull(),
            'id_product'=>$this->integer(5)->unsigned()->notNull(),
            'active'=>$this->boolean()->notNull()->defaultValue(false)
        ], 'ENGINE=InnoDB');
        
        $this->addForeignKey('comments_id_email', 'comments', 'id_email', 'emails', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('comments_id_product', 'comments', 'id_product', 'products', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('comments_id_product', 'comments');
        
        $this->dropForeignKey('comments_id_email', 'comments');
        
        $this->dropTable('comments');
    }
}
