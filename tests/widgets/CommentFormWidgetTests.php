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
        $this->assertTrue($reflection->hasProperty('view'));
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
     * если пуст CommentFormWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
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
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comment-form.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<form id="comment-form"#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<textarea#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
