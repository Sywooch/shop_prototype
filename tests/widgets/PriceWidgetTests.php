<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PriceWidget;
use app\tests\DbManager;

/**
 * Тестирует класс app\widgets\PriceWidget
 */
class PriceWidgetTests extends TestCase
{
    private static $_dbClass;
    private static $_price = 123.3456;
    
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
     * Тестирует метод PriceWidget::widget
     */
    public function testWidget()
    {
        $fixture_1 = self::$_dbClass->currency['currency_1'];
        $fixture_2 = self::$_dbClass->currency['currency_2'];
        
        \Yii::configure(\Yii::$app->currency, $fixture_1);
        
        $result = PriceWidget::widget(['price'=>self::$_price]);
        
        $expectedString = number_format((self::$_price * \Yii::$app->currency->exchange_rate), 2, ',', '') . ' ' . \Yii::$app->currency->code;
        
        $this->assertEquals($expectedString, $result);
        
        \Yii::configure(\Yii::$app->currency, $fixture_2);
        
        $result = PriceWidget::widget(['price'=>self::$_price]);
        
        $expectedString = number_format((self::$_price * \Yii::$app->currency->exchange_rate), 2, ',', '') . ' ' . \Yii::$app->currency->code;
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
