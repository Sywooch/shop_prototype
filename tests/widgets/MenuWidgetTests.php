<?php

namespace app\widgets;

use yii\helpers\Url;
use app\tests\MockModel;
use app\widgets\MenuWidget;

/**
 * Тестирует класс app\widgets\MenuWidget
 */
class MenuWidgetTests extends \PHPUnit_Framework_TestCase
{
    private static $_route = '/products-list/index';
    private static $_categoriesName = 'Мужская одежда';
    private static $_subcategoryName = 'Рубашки';
    private static $_categoriesSeocode = 'menswear';
    private static $_subcategorySeocode = 'shirts';
    
    /**
     * Тестирует метод BreadcrumbsWidget::widget()
     */
    public function testWidget()
    {
        $subcategoryModel = new MockModel(['name'=>self::$_subcategoryName, 'seocode'=>self::$_subcategorySeocode]);
        $categoriesModel = new MockModel(['name'=>self::$_categoriesName, 'seocode'=>self::$_categoriesSeocode, 'subcategory'=>[$subcategoryModel]]);
        
        $result = MenuWidget::widget(['route'=>self::$_route, 'objectsList'=>[$categoriesModel]]);
        
        $expectedUrl = '<ul><li><a href="' . Url::home() . 'products/' . self::$_categoriesSeocode . '">' . self::$_categoriesName . '</a><ul><li><a href="' . Url::home() . 'products/' . self::$_categoriesSeocode . '/' . self::$_subcategorySeocode . '">' . self::$_subcategoryName . '</a></li></ul></li></ul>';
        
        $this->assertEquals($expectedUrl, $result);
    }
}
