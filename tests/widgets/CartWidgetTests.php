<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\widgets\CartWidget;
use app\models\{CurrencyModel,
    UsersModel};

class CartWidgetTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>'app\tests\sources\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $currencyQuery = CurrencyModel::find();
        $currencyQuery->extendSelect(['id', 'code', 'exchange_rate', 'main']);
        $currencyQuery->where(['[[currency.id]]'=>$fixture['id']]);
        $currencyModel = $currencyQuery->one();
        $currency = $currencyModel->attributes;
        \Yii::configure(\Yii::$app->currency, $currency);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     */
    public function testWidget()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        \Yii::$app->params['cartArray'] = [
            ['id_product'=>1, 'quantity'=>2, 'id_color'=>2, 'id_size'=>1, 'price'=>(float) 12.34]
        ];
        
        $result = CartWidget::widget();
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>\Yii::$app->params['cartArray'][0]['quantity'] , 'totalCost'=>number_format((\Yii::$app->params['cartArray'][0]['price'] * \Yii::$app->params['cartArray'][0]['quantity']), 2, ',', '')]);
        
        $expectedString = '<div id="cart"><p>' . $text . ' ' . $fixture['code'] . ' <a href="../vendor/phpunit/phpunit/cart">' . \Yii::t('base', 'To cart') . '</a></p><form id="clean-cart-form" action="../vendor/phpunit/phpunit/clean-cart" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><button type="submit">' . \Yii::t('base', 'Clean') . '</button></form></div>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод CartWidget::widget() 
     * при условии, что массив \Yii::$app->params['cartArray'] пуст
     */
    public function testWidgetTwo()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        \Yii::$app->params['cartArray'] = [];
         
        $result = CartWidget::widget();
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>0, 'totalCost'=>number_format(0, 2, ',', '')]);
        
        $expectedString = '<div id="cart"><p>' . $text . ' ' . $fixture['code'] . '</p><form id="clean-cart-form" action="../vendor/phpunit/phpunit/clean-cart" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><button type="submit" disabled>' . \Yii::t('base', 'Clean') . '</button></form></div>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод CartWidget::widget() 
     * при условии, что CartWidget::toCart = false
     */
    public function testWidgetThree()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        \Yii::$app->params['cartArray'] = [
            ['id_product'=>1, 'quantity'=>2, 'id_color'=>2, 'id_size'=>1, 'price'=>(float) 12.34]
        ];
        
        $result = CartWidget::widget(['toCart'=>false]);
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>\Yii::$app->params['cartArray'][0]['quantity'] , 'totalCost'=>number_format((\Yii::$app->params['cartArray'][0]['price'] * \Yii::$app->params['cartArray'][0]['quantity']), 2, ',', '')]);
        
        $expectedString = '<div id="cart"><p>' . $text . ' ' . $fixture['code'] . '</p><form id="clean-cart-form" action="../vendor/phpunit/phpunit/clean-cart" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><button type="submit">' . \Yii::t('base', 'Clean') . '</button></form></div>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
