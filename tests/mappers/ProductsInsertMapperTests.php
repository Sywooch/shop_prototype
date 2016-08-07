<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ProductsInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsInsertMapper
 */
class ProductsInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_date = 'YU-6709';
    private static $_code = 'code';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_id_categories = 1;
    private static $_id_subcategory = 1;
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id_categories, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id_subcategory, ':name'=>self::$_name, ':id_categories'=>self::$_id_categories, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $usersInsertMapper = new ProductsInsertMapper([
            'tableName'=>'products',
            'fields'=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'objectsArray'=>[
                new MockModel([
                    'date'=>self::$_date,
                    'code'=>self::$_code,
                    'name'=>self::$_name,
                    'description'=>self::$_description,
                    'short_description'=>self::$_description,
                    'price'=>self::$_price,
                    'images'=>self::$_images,
                    'id_categories'=>self::$_id_categories,
                    'id_subcategory'=>self::$_id_subcategory,
                ]),
            ],
        ]);
        $result = $usersInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE code=:code');
        $command->bindValue(':code', self::$_code);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('short_description', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('images', $result);
        $this->assertArrayHasKey('id_categories', $result);
        $this->assertArrayHasKey('id_subcategory', $result);
        
        $this->assertEquals(self::$_code, $result['code']);
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_description, $result['description']);
        $this->assertEquals(self::$_description, $result['short_description']);
        $this->assertEquals(self::$_price, $result['price']);
        $this->assertEquals(self::$_images, $result['images']);
        $this->assertEquals(self::$_id_categories, $result['id_categories']);
        $this->assertEquals(self::$_id_subcategory, $result['id_subcategory']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
