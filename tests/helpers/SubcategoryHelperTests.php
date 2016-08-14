<?php

namespace app\tests\helpers;

use app\helpers\{SubcategoryHelper,
    MappersHelper};
use app\tests\DbManager;

/**
 * Тестирует app\helpers\SubcategoryHelper
 */
class SubcategoryHelperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_categoryName = 'Some category';
    private static $_subcategoryName = 'Some subcategory';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_mock = 'mock';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_categoryName, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_subcategoryName, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategoryHelper::getSubcategory
     */
    public function testGetSubcategory()
    {
        $result = SubcategoryHelper::getSubcategory(self::$_id);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertTrue(array_key_exists(self::$_id, $result));
        $this->assertEquals(self::$_subcategoryName,  $result[self::$_id]);
    }
    
    /**
     * Тестирует метод SubcategoryHelper::getSubcategory
     * при передаче неверных значений
     */
    public function testGetSubcategoryForEmpty()
    {
        $result = SubcategoryHelper::clean();
        
        $this->assertTrue($result);
        
        $result = SubcategoryHelper::getSubcategory(self::$_mock);
        
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
        
        $result = SubcategoryHelper::clean();
        
        $this->assertTrue($result);
        
        $result = SubcategoryHelper::getSubcategory(null);
        
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
