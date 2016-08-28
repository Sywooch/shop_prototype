<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\InsertCategoriesMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\InsertCategoriesMapper
 */
class InsertCategoriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_name = 'Очки';
    private static $_seocode = 'glasses';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод InsertCategoriesMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll()));
        
        $categoriesInsertMapper = new InsertCategoriesMapper([
            'tableName'=>'categories',
            'fields'=>['name', 'seocode'],
            'objectsArray'=>[
                new MockModel([
                    'name'=>self::$_name, 
                    'seocode'=>self::$_seocode
                ])
            ],
        ]);
        
        $result = $categoriesInsertMapper->setGroup();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[categories.name]]=:name');
        $command->bindValue(':name', self::$_name);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_name, $result['name']);
        $this->assertEquals(self::$_seocode, $result['seocode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
