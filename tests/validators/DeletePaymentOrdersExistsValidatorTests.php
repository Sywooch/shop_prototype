<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\DeletePaymentOrdersExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс DeletePaymentOrdersExistsValidator
 */
class DeletePaymentOrdersExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'orders'=>PurchasesFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new DeletePaymentOrdersExistsValidator();
    }
    
    /**
     * Тестирует метод DeletePaymentOrdersExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $this->validator->validateAttribute($model, 'id');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
