<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\DeleteCategoriesMapper;
use app\helpers\MappersHelper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\mappers\DeleteCategoriesMapper
 */
class DeleteCategoriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 3;
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    
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
     * Тестирует метод DeleteCategoriesMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll()));
        
        $categoriesDeleteMapper = new DeleteCategoriesMapper([
            'tableName'=>'categories',
            'objectsArray'=>[
                new CategoriesModel(['id'=>self::$_id]),
            ],
        ]);
        
        $result = $categoriesDeleteMapper->setGroup();
        
        $this->assertEquals(1, $result);
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{categories}}')->queryAll()));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
