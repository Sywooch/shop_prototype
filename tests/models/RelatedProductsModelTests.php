<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\RelatedProductsModel;

/**
 * Тестирует класс app\models\RelatedProductsModel
 */
class RelatedProductsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'related_products'=>'app\tests\sources\fixtures\RelatedProductsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\RelatedProductsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\RelatedProductsModel
     */
    public function testProperties()
    {
        $model = new RelatedProductsModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_related_product', $model->attributes));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
