<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\CategoriesUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\CategoriesUpdateMapper
 */
class CategoriesUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_name = 'name';
    private static $_name2 = 'another name';
    private static $_seocode = 'some seocode';
    private static $_seocode2 = 'another seocode';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_seocode]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CategoriesUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $categoriesUpdateMapper = new CategoriesUpdateMapper([
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'name'=>self::$_name2, 
                    'seocode'=>self::$_seocode2, 
                ]),
            ],
        ]);
        $result = $categoriesUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{categories}} WHERE [[categories.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_name2, $result['name']);
        $this->assertEquals(self::$_seocode2, $result['seocode']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
