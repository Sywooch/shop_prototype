<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SubcategoryBySeocodeMapper;
use app\models\SubcategoryModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SubcategoryBySeocodeMapper
 */
class SubcategoryBySeocodeMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
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
     * Тестирует метод SubcategoryBySeocodeMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $subcategoryBySeocodeMapper = new SubcategoryBySeocodeMapper([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
            'model'=>new SubcategoryModel([
                'seocode'=>self::$_subcategorySeocode,
            ]),
        ]);
        $subcategoryModel = $subcategoryBySeocodeMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($subcategoryModel));
        $this->assertTrue($subcategoryModel instanceof SubcategoryModel);
        
        $this->assertTrue(property_exists($subcategoryModel, 'id'));
        $this->assertTrue(property_exists($subcategoryModel, 'name'));
        $this->assertTrue(property_exists($subcategoryModel, 'seocode'));
        $this->assertTrue(property_exists($subcategoryModel, 'id_categories'));
        
        $this->assertFalse(empty($subcategoryModel->id));
        $this->assertFalse(empty($subcategoryModel->name));
        $this->assertFalse(empty($subcategoryModel->seocode));
        $this->assertFalse(empty($subcategoryModel->id_categories));
        
        $this->assertEquals(self::$_id, $subcategoryModel->id);
        $this->assertEquals(self::$_name, $subcategoryModel->name);
        $this->assertEquals(self::$_subcategorySeocode, $subcategoryModel->seocode);
        $this->assertEquals(self::$_id, $subcategoryModel->id_categories);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
