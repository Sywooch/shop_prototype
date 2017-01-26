<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentFormWidget;
use app\controllers\ProductDetailController;
use app\forms\CommentForm;

/**
 * Тестирует класс CommentFormWidget
 */
class CommentFormWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentFormWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentFormWidget::class);
        
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод CommentFormWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new CommentFormWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CommentFormWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends CommentForm {};
        
        $widget = new CommentFormWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CommentForm::class, $result);
    }
    
    /**
     * Тестирует метод CommentFormWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new CommentFormWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод CommentFormWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new CommentFormWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CommentFormWidget::run
     * если пуст CommentFormWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $widget = new CommentFormWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentFormWidget::run
     * если пуст CommentFormWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $form = new class() extends CommentForm {};
        
        $widget = new CommentFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentFormWidget::run
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $form = new class() extends CommentForm {};
        
        $widget = new CommentFormWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comment-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="comment-form"#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<textarea#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
