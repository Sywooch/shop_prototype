<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\ProductsUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsUpdateMapper
 */
class ProductsUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_name2 = 'another name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_price2 = 568.12;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_active = true;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id, ':active'=>self::$_active]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $productsUpdateMapper = new ProductsUpdateMapper([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'date'=>self::$_date, 
                    'code'=>self::$_code, 
                    'name'=>self::$_name2, 
                    'description'=>self::$_description, 
                    'short_description'=>self::$_description, 
                    'price'=>self::$_price2, 
                    'images'=>self::$_images, 
                    'id_categories'=>self::$_id, 
                    'id_subcategory'=>self::$_id, 
                    'active'=>self::$_active,
                ]),
            ],
        ]);
        $result = $productsUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{products}} WHERE [[products.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('short_description', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('images', $result);
        $this->assertArrayHasKey('id_categories', $result);
        $this->assertArrayHasKey('id_subcategory', $result);
        $this->assertArrayHasKey('active', $result);
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_date, $result['date']);
        $this->assertEquals(self::$_code, $result['code']);
        $this->assertEquals(self::$_name2, $result['name']);
        $this->assertEquals(self::$_description, $result['description']);
        $this->assertEquals(self::$_description, $result['short_description']);
        $this->assertEquals(self::$_price2, $result['price']);
        $this->assertEquals(self::$_images, $result['images']);
        $this->assertEquals(self::$_id, $result['id_categories']);
        $this->assertEquals(self::$_id, $result['id_subcategory']);
        $this->assertEquals(self::$_active, $result['active']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
