<?php

namespace app\tests\validators;

use app\tests\DbManager;
use app\validators\CurrencyCurrencyExistsValidator;
use app\helpers\MappersHelper;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\validators\CurrencyCurrencyExistsValidator
 */
class CurrencyCurrencyExistsValidatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = true;
    
    private static $_message = 'Валюта с таким именем уже добавлена!';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод CurrencyCurrencyExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new CurrencyModel();
        $model->currency = self::$_currency;
        
        $validator = new CurrencyCurrencyExistsValidator();
        $validator->validateAttribute($model, 'currency');
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('currency', $model->errors));
        $this->assertEquals(1, count($model->errors['currency']));
        $this->assertEquals(self::$_message, $model->errors['currency'][0]);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
