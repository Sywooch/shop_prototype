<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SubcategoryDeleteMapper;
use app\helpers\MappersHelper;
use app\models\SubcategoryModel;

/**
 * Тестирует класс app\mappers\SubcategoryDeleteMapper
 */
class SubcategoryDeleteMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 3;
    private static $_name = 'Some Name';
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
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategoryDeleteMapper::setGroup
     */
    public function testSetGroup()
    {
        $this->assertFalse(empty(\Yii::$app->db->createCommand('SELECT * FROM {{subcategory}}')->queryAll()));
        
        $subcategoryDeleteMapper = new SubcategoryDeleteMapper([
            'tableName'=>'subcategory',
            'objectsArray'=>[
                new SubcategoryModel(['id'=>self::$_id]),
            ],
        ]);
        
        $result = $subcategoryDeleteMapper->setGroup();
        
        $this->assertEquals(1, $result);
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{subcategory}}')->queryAll()));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
