<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\AddressModel;

/**
 * Тестирует класс app\models\AddressModel
 */
class AddressModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'address'=>'app\tests\sources\fixtures\AddressFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\AddressModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\AddressModel
     */
    public function testProperties()
    {
        $model = new AddressModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('address', $model->attributes));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
