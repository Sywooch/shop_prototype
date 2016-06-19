<?php

namespace app\tests\some;

use app\tests\DbManager;
use app\mappers\BrandsMapper;
use app\models\BrandsModel;

/**
 * Тестирует класс app\mappers\BrandsMapper
 */
class BrandsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{brands}} SET [[id]]=:id, [[brand]]=:brand');
        $command->bindValues([':id'=>1, ':brand'=>'Some Brand']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>1, ':name'=>'Some Categories', ':seocode'=>'mensfootwear']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>1, ':name'=>'Some Subcategory', ':id_categories'=>1, ':seocode'=>'boots']);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>1, ':name'=>'Some Name', ':id_categories'=>1, ':id_subcategory'=>1]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_brands}} SET [[id_products]]=:id_products, [[id_brands]]=:id_brands');
        $command->bindValues([':id_products'=>1, ':id_brands'=>1]);
        $command->execute();
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    /**
     * Тестирует метод BrandsMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        $brandsList = $brandsMapper->getGroup();
        
        $this->assertTrue(is_array($brandsList));
        $this->assertFalse(empty($brandsList));
        $this->assertTrue(is_object($brandsList[0]));
        $this->assertTrue($brandsList[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsList[0], 'id'));
        $this->assertTrue(property_exists($brandsList[0], 'brand'));
        
        $this->assertTrue(isset($brandsList[0]->id));
        $this->assertTrue(isset($brandsList[0]->brand));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
