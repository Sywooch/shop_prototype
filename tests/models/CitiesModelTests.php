<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\CitiesModel;

/**
 * Тестирует класс app\models\CitiesModel
 */
class CitiesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'cities'=>'app\tests\sources\fixtures\CitiesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CitiesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CitiesModel
     */
    public function testProperties()
    {
        $model = new CitiesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('city', $model->attributes));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
