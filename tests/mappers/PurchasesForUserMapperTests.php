<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\PurchasesForUserMapper;
use app\models\{UsersModel, 
    PurchasesModel};
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\PurchasesForUserMapper
 */
class PurchasesForUserMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id_emails = 34;
    private static $_name = 'Some Name';
    private static $_surname = 'Some Surname';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_description = 'Some description';
    private static $_price = 12.34;
    private static $_received = 1;
    private static $_received_date = 1462453595;
    private static $_processed = 0;
    private static $_canceled = 0;
    private static $_shipped = 0;
    private static $_color = 'gray';
    private static $_size = '46';
    private static $_quantity = 2;
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id_emails, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id_emails, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{deliveries}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description, [[price]]=:price');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{payments}} SET [[id]]=:id, [[name]]=:name, [[description]]=:description');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':description'=>self::$_description]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{purchases}} SET [[id]]=:id, [[id_users]]=:id_users, [[id_products]]=:id_products, [[quantity]]=:quantity, [[id_colors]]=:id_colors, [[id_sizes]]=:id_sizes, [[id_deliveries]]=:id_deliveries, [[id_payments]]=:id_payments, [[received]]=:received, [[received_date]]=:received_date, [[processed]]=:processed, [[canceled]]=:canceled, [[shipped]]=:shipped');
        $command->bindValues([':id'=>self::$_id, ':id_users'=>self::$_id, ':id_products'=>self::$_id, ':quantity'=>self::$_quantity, ':id_colors'=>self::$_id, ':id_sizes'=>self::$_id, ':id_deliveries'=>self::$_id, ':id_payments'=>self::$_id, ':received'=>self::$_received, ':received_date'=>self::$_received_date, ':processed'=>self::$_processed, ':canceled'=>self::$_canceled, ':shipped'=>self::$_shipped]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод PurchasesForUserMapper::getGroup
     */
    public function testGetGroup()
    {
        $purchasesForUserMapper = new PurchasesForUserMapper([
            'tableName'=>'purchases',
            'fields'=>['id', 'id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
            'model'=>new UsersModel([
                'id'=>self::$_id,
            ]),
        ]);
        $purchasesList = $purchasesForUserMapper->getGroup();
        
        $this->assertTrue(is_array($purchasesList));
        $this->assertFalse(empty($purchasesList));
        $this->assertTrue(is_object($purchasesList[0]));
        $this->assertTrue($purchasesList[0] instanceof PurchasesModel);
        
        $this->assertTrue(property_exists($purchasesList[0], 'id'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_users'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_products'));
        $this->assertTrue(property_exists($purchasesList[0], 'quantity'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_colors'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_sizes'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_deliveries'));
        $this->assertTrue(property_exists($purchasesList[0], 'id_payments'));
        
        $this->assertTrue(isset($purchasesList[0]->id));
        $this->assertTrue(isset($purchasesList[0]->id_users));
        $this->assertTrue(isset($purchasesList[0]->id_products));
        $this->assertTrue(isset($purchasesList[0]->quantity));
        $this->assertTrue(isset($purchasesList[0]->id_colors));
        $this->assertTrue(isset($purchasesList[0]->id_sizes));
        $this->assertTrue(isset($purchasesList[0]->id_deliveries));
        $this->assertTrue(isset($purchasesList[0]->id_payments));
        $this->assertTrue(isset($purchasesList[0]->received));
        $this->assertTrue(isset($purchasesList[0]->received_date));
        $this->assertTrue(isset($purchasesList[0]->processed));
        $this->assertTrue(isset($purchasesList[0]->canceled));
        $this->assertTrue(isset($purchasesList[0]->shipped));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
