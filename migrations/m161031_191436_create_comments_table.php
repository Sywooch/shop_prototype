<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comments`.
 */
class m161031_191436_create_comments_table extends Migration
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
            'id_name'=>$this->integer(5)->unsigned()->notNull(),
            'id_email'=>$this->integer(5)->unsigned()->notNull(),
            'id_product'=>$this->integer(5)->unsigned()->notNull(),
            'active'=>$this->boolean()->notNull()->defaultValue(false)
        ], 'ENGINE=InnoDB');
        
        $this->addForeignKey('comments_id_name', 'comments', 'id_name', 'names', 'id', 'RESTRICT', 'CASCADE');
        
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
        
        $this->dropForeignKey('comments_id_name', 'comments');
        
        $this->dropTable('comments');
    }
}
