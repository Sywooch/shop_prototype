<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\BrandsModel;

/**
 * Тестирует класс app\models\BrandsModel
 */
class BrandsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>'app\tests\sources\fixtures\BrandsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\BrandsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\BrandsModel
     */
    public function testProperties()
    {
        $model = new BrandsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('brand', $model->attributes));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
