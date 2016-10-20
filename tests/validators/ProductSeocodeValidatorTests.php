<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Object;
use app\tests\DbManager;
use app\validators\ProductSeocodeValidator;

/**
 * Тестирует класс app\validators\ProductSeocodeValidator
 */
class ProductSeocodeValidatorTests extends TestCase
{
    private static $_dbClass;
    private static $_name = 'Коричневый шерстяной шарф';
    private static $_expectedSeocode = 'korichnevyi-sherstyanoi-sharf';
    private static $_existsSeocode = 'exist-seocode-ready';
    private static $_code = 'UYJ98O';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductSeocodeValidator::validateAttribute
     */
     public function testValidateAttribute()
    {
        $model = new class(['name'=>self::$_name, 'code'=>self::$_code]) extends Object {
            public $name;
            public $code;
            public $seocode;
        };
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals(self::$_expectedSeocode, $model->seocode);
        
        $model = new class(['name'=>self::$_name, 'code'=>self::$_code, 'seocode'=>self::$_existsSeocode]) extends Object {
            public $name;
            public $code;
            public $seocode;
        };
        
        $validator = new ProductSeocodeValidator();
        $validator->validateAttribute($model, 'seocode');
        
        $this->assertEquals(self::$_existsSeocode, $model->seocode);
    }
    
    public static function tearDownAfterCLass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
