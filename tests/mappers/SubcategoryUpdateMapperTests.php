<?php

namespace app\tests\mappers;

use app\tests\{DbManager, 
    MockModel};
use app\mappers\SubcategoryUpdateMapper;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SubcategoryUpdateMapper
 */
class SubcategoryUpdateMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_id2 = 15;
    private static $_name = 'name';
    private static $_name2 = 'another name';
    private static $_name3 = 'another name 3';
    private static $_categorySeocode = 'mensfootwear';
    private static $_categorySeocode2 = 'mensfootwear 2';
    private static $_subcategorySeocode = 'boots';
    private static $_subcategorySeocode2 = 'boots 2';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id2, ':name'=>self::$_name3, ':seocode'=>self::$_categorySeocode2]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode, [[id_categories]]=:id_categories');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_subcategorySeocode, ':id_categories'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SubcategoryUpdateMapper::setGroup
     */
    public function testSetGroup()
    {
        $subcategoryUpdateMapper = new SubcategoryUpdateMapper([
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_id, 
                    'name'=>self::$_name2, 
                    'seocode'=>self::$_subcategorySeocode2,
                    'id_categories'=>self::$_id2,
                ]),
            ],
        ]);
        $result = $subcategoryUpdateMapper->setGroup();
        
        $this->assertEquals(2, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{subcategory}} WHERE [[subcategory.id]]=:id');
        $command->bindValue(':id', self::$_id);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertEquals(self::$_id, $result['id']);
        $this->assertEquals(self::$_name2, $result['name']);
        $this->assertEquals(self::$_subcategorySeocode2, $result['seocode']);
        $this->assertEquals(self::$_id2, $result['id_categories']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
