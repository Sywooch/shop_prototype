<?php

use yii\db\Migration;

/**
 * Handles the creation of table `purchases`.
 */
class m161031_202457_create_purchases_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('purchases', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'id_user'=>$this->integer(5)->unsigned()->notNull(),
            'id_name'=>$this->integer(5)->unsigned()->notNull(),
            'id_surname'=>$this->integer(5)->unsigned()->notNull(),
            'id_email'=>$this->integer(5)->unsigned()->notNull(),
            'id_phone'=>$this->integer(5)->unsigned()->notNull(),
            'id_address'=>$this->integer(5)->unsigned()->notNull(),
            'id_city'=>$this->integer(3)->unsigned()->notNull(),
            'id_country'=>$this->integer(3)->unsigned()->notNull(),
            'id_postcode'=>$this->integer(5)->unsigned()->notNull(),
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
        
        $this->addForeignKey('purchases_id_name', 'purchases', 'id_name', 'names', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_surname', 'purchases', 'id_surname', 'surnames', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_product', 'purchases', 'id_product', 'products', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_color', 'purchases', 'id_color', 'colors', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_size', 'purchases', 'id_size', 'sizes', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_delivery', 'purchases', 'id_delivery', 'deliveries', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_payment', 'purchases', 'id_payment', 'payments', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_email', 'purchases', 'id_email', 'emails', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_phone', 'purchases', 'id_phone', 'phones', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_address', 'purchases', 'id_address', 'address', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_city', 'purchases', 'id_city', 'cities', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_country', 'purchases', 'id_country', 'countries', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('purchases_id_postcode', 'purchases', 'id_postcode', 'countries', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('purchases_id_postcode', 'purchases');
        
        $this->dropForeignKey('purchases_id_country', 'purchases');
        
        $this->dropForeignKey('purchases_id_city', 'purchases');
        
        $this->dropForeignKey('purchases_id_address', 'purchases');
        
        $this->dropForeignKey('purchases_id_phone', 'purchases');
        
        $this->dropForeignKey('purchases_id_email', 'purchases');
        
        $this->dropForeignKey('purchases_id_payment', 'purchases');
        
        $this->dropForeignKey('purchases_id_delivery', 'purchases');
        
        $this->dropForeignKey('purchases_id_size', 'purchases');
        
        $this->dropForeignKey('purchases_id_color', 'purchases');
        
        $this->dropForeignKey('purchases_id_product', 'purchases');
        
        $this->dropForeignKey('purchases_id_surname', 'purchases');
        
        $this->dropForeignKey('purchases_id_name', 'purchases');
        
        $this->dropTable('purchases');
    }
}
