<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ColorsModel;

/**
 * Тестирует класс app\models\ColorsModel
 */
class ColorsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>'app\tests\sources\fixtures\ColorsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ColorsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ColorsModel
     */
    public function testProperties()
    {
        $model = new ColorsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('color', $model->attributes));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
