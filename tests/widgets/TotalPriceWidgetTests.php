<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\TotalPriceWidget;
use app\tests\DbManager;

/**
 * Тестирует класс app\widgets\TotalPriceWidget
 */
class TotalPriceWidgetTests extends TestCase
{
    private static $_dbClass;
    private static $_price = 123.3456;
    private static $_quantity = 3;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>'app\tests\sources\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод TotalPriceWidget::widget
     */
    public function testWidget()
    {
        $fixture_1 = self::$_dbClass->currency['currency_1'];
        $fixture_2 = self::$_dbClass->currency['currency_2'];
        
        \Yii::configure(\Yii::$app->currency, $fixture_1);
        
        $result = TotalPriceWidget::widget(['price'=>self::$_price, 'quantity'=>self::$_quantity]);
        
        $expectedString = number_format(((self::$_price * self::$_quantity) * \Yii::$app->currency->exchange_rate), 2, ',', '') . ' ' . \Yii::$app->currency->code;
        
        $this->assertEquals($expectedString, $result);
        
        \Yii::configure(\Yii::$app->currency, $fixture_2);
        
        $result = TotalPriceWidget::widget(['price'=>self::$_price, 'quantity'=>self::$_quantity]);
        
        $expectedString = number_format(((self::$_price * self::$_quantity) * \Yii::$app->currency->exchange_rate), 2, ',', '') . ' ' . \Yii::$app->currency->code;
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
