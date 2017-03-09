<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentSaveSuccessWidget;

/**
 * Тестирует класс CommentSaveSuccessWidget
 */
class CommentSaveSuccessWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentSaveSuccessWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentSaveSuccessWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CommentSaveSuccessWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CommentSaveSuccessWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::run
     * если пуст CommentSaveSuccessWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new CommentSaveSuccessWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CommentSaveSuccessWidget::run
     */
    public function testRun()
    {
        $widget = new CommentSaveSuccessWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'paragraph.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p>Комментарий сохранен и будет доступен после проверки модератором. Спасибо!</p>#', $result);
    }
}
