<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\ColorsAdminMapper;
use app\models\ColorsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ColorsAdminMapper
 */
class ColorsAdminMapperTests extends \PHPUnit_Framework_TestCase
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
        
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ColorsAdminMapper::getGroup
     */
    public function testGetGroup()
    {
        $colorsMapper = new ColorsAdminMapper([
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
    }
    
    /**
     * Тестирует метод ColorsAdminMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        
        $colorsMapper = new ColorsAdminMapper([
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
    }
    
    /**
     * Тестирует метод ColorsAdminMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        \Yii::$app->filters->subcategory = 'boots';
        
        $colorsMapper = new ColorsAdminMapper([
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
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
