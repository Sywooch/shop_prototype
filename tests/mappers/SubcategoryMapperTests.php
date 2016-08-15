<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\SubcategoryMapper;
use app\models\SubcategoryModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SubcategoryMapper
 */
class SubcategoryMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
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
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode, [[id_categories]]=:id_categories');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_subcategorySeocode, ':id_categories'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategoryMapper::getGroup
     */
    public function testGetGroup()
    {
        $subcategoryMapper = new SubcategoryMapper([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
        ]);
        $subcategoryList = $subcategoryMapper->getGroup();
        
        $this->assertTrue(is_array($subcategoryList));
        $this->assertFalse(empty($subcategoryList));
        $this->assertTrue(is_object($subcategoryList[0]));
        $this->assertTrue($subcategoryList[0] instanceof SubcategoryModel);
        
        $this->assertFalse(empty($subcategoryList[0]->id));
        $this->assertFalse(empty($subcategoryList[0]->name));
        $this->assertFalse(empty($subcategoryList[0]->seocode));
        $this->assertFalse(empty($subcategoryList[0]->id_categories));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
