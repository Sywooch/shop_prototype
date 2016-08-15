<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SubcategoryInsertMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SubcategoryInsertMapper
 */
class SubcategoryInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Name';
    private static $_categorySeocode = 'glasses';
    private static $_subcategorySeocode = 'sunglasses';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategoryInsertMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{subcategory}}')->queryAll()));
        
        $subcategoryInsertMapper = new SubcategoryInsertMapper([
            'tableName'=>'subcategory',
            'fields'=>['name', 'seocode', 'id_categories'],
            'objectsArray'=>[
                new MockModel([
                    'name'=>self::$_name, 
                    'seocode'=>self::$_subcategorySeocode,
                    'id_categories'=>self::$_id
                ])
            ],
        ]);
        
        $result = $subcategoryInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[subcategory.name]]=:name');
        $command->bindValue(':name', self::$_name);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_subcategorySeocode, $result['seocode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
