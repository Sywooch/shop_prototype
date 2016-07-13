<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\ProductDetailMapper;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\ProductDetailMapper
 */
class ProductDetailMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_code = 'Fghr8';
    private static $_name = 'Some Name';
    private static $_description = 'Some description';
    private static $_price = 123.34;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод ProductDetailMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = ['id'=>1];
        
        $productMapper = new ProductDetailMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        ]);
        $objectProductsArray = $productMapper->getGroup();
        
        $this->assertTrue(is_array($objectProductsArray));
        $this->assertFalse(empty($objectProductsArray));
        $this->assertTrue(is_object($objectProductsArray[0]));
        $this->assertTrue($objectProductsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($objectProductsArray[0], 'id'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'code'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'name'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'description'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'price'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'images'));
        
        $this->assertFalse(empty($objectProductsArray[0]->id));
        $this->assertFalse(empty($objectProductsArray[0]->code));
        $this->assertFalse(empty($objectProductsArray[0]->name));
        $this->assertFalse(empty($objectProductsArray[0]->description));
        $this->assertFalse(empty($objectProductsArray[0]->price));
        $this->assertFalse(empty($objectProductsArray[0]->images));
        
        $this->assertEquals(self::$_id, $objectProductsArray[0]->id);
        $this->assertEquals(self::$_code, $objectProductsArray[0]->code);
        $this->assertEquals(self::$_name, $objectProductsArray[0]->name);
        $this->assertEquals(self::$_description, $objectProductsArray[0]->description);
        $this->assertEquals(self::$_price, $objectProductsArray[0]->price);
        $this->assertEquals(self::$_images, $objectProductsArray[0]->images);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
