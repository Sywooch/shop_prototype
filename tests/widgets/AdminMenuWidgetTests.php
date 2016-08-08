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
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     */
    public function testWidget()
    {
        $model = new MockModel(['name'=>self::$_name, 'route'=>self::$_route]);
        
        $result = AdminMenuWidget::widget(['objectsList'=>[$model]]);
        
        $expectedUrl = '<ul><li><a href="' . Url::home() . 'admin">' . self::$_name . '</a></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
    }
}
