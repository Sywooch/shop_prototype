<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\models\UsersModel;

class CartWidgetTests extends TestCase
{
    /**
     * Тестирует метод CartWidget::widget()
     */
    public function testWidget()
    {
        \Yii::$app->params['cartArray'] = [
            ['id_product'=>1, 'quantity'=>2, 'id_color'=>2, 'id_size'=>1, 'price'=>12.34]
        ];
        
        $result = CartWidget::widget();
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>\Yii::$app->params['cartArray'][0]['quantity'] , 'totalCost'=>(\Yii::$app->params['cartArray'][0]['price'] * \Yii::$app->params['cartArray'][0]['quantity'])]);
        
        $expectedString = '<p>' . $text . ' <a href="../vendor/phpunit/phpunit/cart">' . \Yii::t('base', 'To cart') . '</a></p><form id="clean-cart-form" action="../vendor/phpunit/phpunit/clean-cart" method="POST"' . ">\n" . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><button type="submit">' . \Yii::t('base', 'Clean') . '</button></form>';
        
        $this->assertEquals($expectedString, $result);
    }
    
    /**
     * Тестирует метод CartWidget::widget() 
     * при условии, что массив \Yii::$app->params['cartArray'] пуст
     */
    public function testWidgetTwo()
    {
        \Yii::$app->params['cartArray'] = [];
         
        $result = CartWidget::widget();
        
        $this->assertEquals('', $result);
    }
}
