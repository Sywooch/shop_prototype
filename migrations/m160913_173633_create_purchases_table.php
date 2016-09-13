<?php

use yii\db\Migration;

/**
 * Handles the creation for table `purchases`.
 */
class m160913_173633_create_purchases_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('purchases', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'id_user'=>$this->integer(5)->unsigned()->notNull(),
            'id_product'=>$this->integer(5)->unsigned()->notNull(),
            'quantity'=>$this->smallInteger(3)->unsigned()->notNull(),
            'id_color'=>$this->integer(3)->unsigned()->notNull(),
            'id_size'=>$this->integer(3)->unsigned()->notNull(),
            'id_delivery'=>$this->integer(3)->unsigned()->notNull(),
            'id_payment'=>$this->integer(3)->unsigned()->notNull(),
            'received'=>$this->boolean()->notNull()->defaultValue(false),
            'received_date'=>$this->integer(10)->unsigned()->notNull(),
            'processed'=>$this->boolean()->notNull()->defaultValue(false),
            'canceled'=>$this->boolean()->notNull()->defaultValue(false),
            'shipped'=>$this->boolean()->notNull()->defaultValue(false)
        ], 'ENGINE=InnoDB');
        
        $this->addForeignKey('purchases_id_user', 'purchases', 'id_user', 'users', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_product', 'purchases', 'id_product', 'products', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_color', 'purchases', 'id_color', 'colors', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_size', 'purchases', 'id_size', 'sizes', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_delivery', 'purchases', 'id_delivery', 'deliveries', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_payment', 'purchases', 'id_payment', 'payments', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('purchases_id_payment', 'purchases');
        
        $this->dropForeignKey('purchases_id_delivery', 'purchases');
        
        $this->dropForeignKey('purchases_id_size', 'purchases');
        
        $this->dropForeignKey('purchases_id_color', 'purchases');
        
        $this->dropForeignKey('purchases_id_product', 'purchases');
        
        $this->dropForeignKey('purchases_id_user', 'purchases');
        
        $this->dropTable('purchases');
    }
}
