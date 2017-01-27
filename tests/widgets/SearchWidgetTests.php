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
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод SearchWidget::setText
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTextError()
    {
        $text = null;
        
        $widget = new SearchWidget();
        $widget->setText($text);
    }
    
    /**
     * Тестирует метод SearchWidget::setText
     */
    public function testSetText()
    {
        $text = 'Text';
        
        $widget = new SearchWidget();
        $widget->setText($text);
        
        $reflection = new \ReflectionProperty($widget, 'text');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SearchWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new SearchWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод SearchWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new SearchWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SearchWidget::run
     * при отсутствии SearchWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
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
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
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
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'search.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="search-form" name="search-form"#', $result);
        $this->assertRegExp('#<input type="text" name="search" value="Some text" size=60 placeholder="Найти">#', $result);
    }
}
