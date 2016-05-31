<?php

namespace app\tests\mappers;

use app\mappers\ColorsForProductMapper;
use app\tests\DbManager;
use app\models\ColorsModel;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\ColorsForProductMapper
 */
class ColorsForProductMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ColorsForProductMapper::getGroup
     */
    public function testGetGroup()
    {
        $colorsForProductMapper = new ColorsForProductMapper([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
            'orderByField'=>'color',
            'productsModel'=>new ProductsModel(['id'=>1]),
        ]);
        $colorsList = $colorsForProductMapper->getGroup();
        
        $this->assertTrue(is_array($colorsList));
        $this->assertFalse(empty($colorsList));
        $this->assertTrue(is_object($colorsList[0]));
        $this->assertTrue($colorsList[0] instanceof ColorsModel);
        
        $this->assertTrue(property_exists($colorsList[0], 'id'));
        $this->assertTrue(property_exists($colorsList[0], 'color'));
        
        $this->assertTrue(isset($colorsList[0]->id));
        $this->assertTrue(isset($colorsList[0]->color));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
