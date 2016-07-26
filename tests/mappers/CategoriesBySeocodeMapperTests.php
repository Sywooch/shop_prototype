<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\CategoriesBySeocodeMapper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\mappers\CategoriesBySeocodeMapper
 */
class CategoriesBySeocodeMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CategoriesBySeocodeMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $categoriesBySeocodeMapper = new CategoriesBySeocodeMapper([
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
            'model'=>new CategoriesModel([
                'seocode'=>self::$_categorySeocode,
            ]),
        ]);
        $categoriesModel = $categoriesBySeocodeMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($categoriesModel));
        $this->assertTrue($categoriesModel instanceof CategoriesModel);
        
        $this->assertTrue(property_exists($categoriesModel, 'id'));
        $this->assertTrue(property_exists($categoriesModel, 'name'));
        $this->assertTrue(property_exists($categoriesModel, 'seocode'));
        
        $this->assertFalse(empty($categoriesModel->id));
        $this->assertFalse(empty($categoriesModel->name));
        $this->assertFalse(empty($categoriesModel->seocode));
        
        $this->assertEquals(self::$_id, $categoriesModel->id);
        $this->assertEquals(self::$_name, $categoriesModel->name);
        $this->assertEquals(self::$_categorySeocode, $categoriesModel->seocode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
