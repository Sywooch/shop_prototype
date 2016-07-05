<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\UsersPurchasesInsertMapper;
use app\models\UsersPurchasesModel;

/**
 * Тестирует класс app\mappers\UsersPurchasesInsertMapper
 */
class UsersPurchasesInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_login = 'Somelogin';
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
    private static $_id_colors = 4;
    private static $_id_sizes = 5;
    private static $_color = 'color';
    private static $_size = 'size';
    private static $_quantity = 2;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id_colors, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id_sizes, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
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
    }
    
    /**
     * Тестирует метод UsersPurchasesInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersPurchasesModel = new UsersPurchasesModel(['scenario'=>UsersPurchasesModel::GET_FROM_FORM]);
        $usersPurchasesModel->attributes = [
            'id_users'=>self::$_id, 
            'id_products'=>self::$_id,
            'quantity'=>self::$_quantity,
            'id_colors'=>self::$_id_colors,
            'id_sizes'=>self::$_id_sizes,
            'id_deliveries'=>self::$_id, 
            'id_payments'=>self::$_id,
        ];
        
        $usersPurchasesInsertMapper = new UsersPurchasesInsertMapper([
            'tableName'=>'users_purchases',
            'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
            'objectsArray'=>[$usersPurchasesModel],
        ]);
        
        $result = $usersPurchasesInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users_purchases}}');
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('id_users', $result);
        $this->assertArrayHasKey('id_products', $result);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertArrayHasKey('id_colors', $result);
        $this->assertArrayHasKey('id_sizes', $result);
        $this->assertArrayHasKey('id_deliveries', $result);
        $this->assertArrayHasKey('id_payments', $result);
        $this->assertArrayHasKey('received', $result);
        $this->assertArrayHasKey('received_date', $result);
        $this->assertArrayHasKey('processed', $result);
        $this->assertArrayHasKey('canceled', $result);
        $this->assertArrayHasKey('shipped', $result);
        
        $this->assertFalse(empty($result['received']));
        $this->assertFalse(empty($result['received_date']));
        
        $this->assertEquals(self::$_id, $result['id_users']);
        $this->assertEquals(self::$_id, $result['id_products']);
        $this->assertEquals(self::$_quantity, $result['quantity']);
        $this->assertEquals(self::$_id_colors, $result['id_colors']);
        $this->assertEquals(self::$_id_sizes, $result['id_sizes']);
        $this->assertEquals(self::$_id, $result['id_deliveries']);
        $this->assertEquals(self::$_id, $result['id_payments']);
        $this->assertEquals(0, $result['processed']);
        $this->assertEquals(0, $result['canceled']);
        $this->assertEquals(0, $result['shipped']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
