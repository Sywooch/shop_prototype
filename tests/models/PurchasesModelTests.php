<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\models\PurchasesModel;

/**
 * Тестирует класс app\models\PurchasesModel
 */
class PurchasesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>'app\tests\sources\fixtures\PurchasesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PurchasesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PurchasesModel
     */
    public function testProperties()
    {
        $model = new PurchasesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
    }
}
