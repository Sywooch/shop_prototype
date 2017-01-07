<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SearchWidget;
use app\controllers\ProductsListController;

/**
 * Тестирует класс SearchWidget
 */
class SearchWidgetTests extends TestCase
{
    /**
     * Тестирует свойства SearchWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SearchWidget::class);
        
        $this->assertTrue($reflection->hasProperty('text'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод SearchWidget::run
     * при отсутствии SearchWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new SearchWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод SearchWidget::run
     * если поиск пуст
     */
    public function testRunEmptySearchText()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $widget = new SearchWidget();
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'search.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="search-form" name="search-form"#', $result);
        $this->assertRegExp('#<input type="text" name="search" value="" size=60 placeholder="Найти">#', $result);
    }
    
    /**
     * Тестирует метод SearchWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $widget = new SearchWidget();
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Some text');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setValue($widget, 'search.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="search-form" name="search-form"#', $result);
        $this->assertRegExp('#<input type="text" name="search" value="Some text" size=60 placeholder="Найти">#', $result);
    }
}
