<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\ColorsMapper;
use app\models\ColorsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsMapper
 */
class ColorsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_color = 'gray';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ColorsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = [];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'queryClass'=>'app\queries\ColorsQueryCreator',
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'queryClass'=>'app\queries\ColorsQueryCreator',
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
    }
    
    /**
     * Тестирует метод SizesMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
        
        $colorsMapper = new ColorsMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'queryClass'=>'app\queries\ColorsQueryCreator',
        ]);
        $colorsList = $colorsMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertFalse(empty($colorsList[0]->id));
        $this->assertFalse(empty($colorsList[0]->color));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
