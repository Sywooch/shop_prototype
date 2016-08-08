<?php

namespace app\widgets;

use yii\helpers\Url;
use app\tests\MockModel;
use app\widgets\AdminMenuWidget;

/**
 * Тестирует класс app\widgets\AdminMenuWidget
 */
class AdminMenuWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_route = '/admin/index';
    private static $_name = 'Главная административного раздела';
    private static $_route2 = '/admin/add-product';
    private static $_name2 = 'А категория';
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     */
    public function testWidget()
    {
        $model = new MockModel(['name'=>self::$_name2, 'route'=>self::$_route2]);
        
        $result = AdminMenuWidget::widget(['objectsList'=>[$model]]);
        
        $expectedUrl = '<ul><li><a href="' . Url::home() . 'admin/add-product">' . self::$_name2 . '</a></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
        
        $model2 = new MockModel(['name'=>self::$_name, 'route'=>self::$_route]);
        
        $result = AdminMenuWidget::widget(['objectsList'=>[$model, $model2]]);
        
        $expectedUrl = '<ul><li><a href="' . Url::home() . 'admin/add-product">' . self::$_name2 . '</a></li><li><a href="' . Url::home() . 'admin">' . self::$_name . '</a></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
        
        $model2 = new MockModel(['name'=>self::$_name, 'route'=>self::$_route]);
        
        $result = AdminMenuWidget::widget(['objectsList'=>[$model, $model2], 'first'=>self::$_name]);
        
        $expectedUrl = '<ul><li><a href="' . Url::home() . 'admin">' . self::$_name . '</a></li><li><a href="' . Url::home() . 'admin/add-product">' . self::$_name2 . '</a></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
    }
}
