<?php

namespace app\tests\mappers;

use app\mappers\SizesForProductMapper;
use app\tests\DbManager;
use app\models\SizesModel;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\SizesForProductMapper
 */
class SizesForProductMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SizesForProductMapper::getGroup
     */
    public function testGetGroup()
    {
        $sizesForProductMapper = new SizesForProductMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size',
            'productsModel'=>new ProductsModel(['id'=>1]),
        ]);
        $sizesList = $sizesForProductMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertTrue(isset($sizesList[0]->id));
        $this->assertTrue(isset($sizesList[0]->size));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
