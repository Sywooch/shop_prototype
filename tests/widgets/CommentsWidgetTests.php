<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CommentsWidget;
use app\controllers\ProductDetailController;
use app\forms\CommentForm;

/**
 * Тестирует класс CommentsWidget
 */
class CommentsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства CommentsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CommentsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('comments'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод CommentsWidget::setComments
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCommentsError()
    {
        $comments = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setComments($comments);
    }
    
    /**
     * Тестирует метод CommentsWidget::setComments
     */
    public function testSetComments()
    {
        $comments = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setComments([$comments]);
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод CommentsWidget::setForm
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new CommentsWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод CommentsWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends CommentForm {};
        
        $widget = new CommentsWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CommentForm::class, $result);
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если пуст CommentsWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: form
     */
    public function testRunEmptyForm()
    {
        $widget = new CommentsWidget();
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если пуст CommentsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: view
     */
    public function testRunEmptyView()
    {
        $form = new class() extends CommentForm {};
        
        $widget = new CommentsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если отсутствуют комментарии
     */
    public function testRunEmptyComments()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $comments = [];
        
        $form = new class() extends CommentForm {};
        
        $widget = new CommentsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $comments);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comments.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Комментарии</strong></p>#', $result);
        $this->assertRegExp('#<form id="add-comment-form"#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<textarea#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
    
    /**
     * Тестирует метод CommentsWidget::run
     * если добавлены комментарии
     */
    public function testRun()
    {
        \Yii::$app->controller = new ProductDetailController('product-detail', \Yii::$app);
        
        $comment1 = new class() {
            public $date = 1477487902;
            public $name;
            public $text = 'Text';
        };
        $reflection = new \ReflectionProperty($comment1, 'name');
        $reflection->setValue($comment1, new class() {
            public $name = 'John';
        });
        
        $comment2 = new class() {
            public $date = 1460581200;
            public $name;
            public $text = 'Text';
        };
        $reflection = new \ReflectionProperty($comment2, 'name');
        $reflection->setValue($comment2, new class() {
            public $name = 'Mary';
        });
        
        $comments = [$comment1, $comment2];
        
        $form = new class() extends CommentForm {};
        
        $widget = new CommentsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'comments');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, $comments);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($widget, 'comments.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Комментарии</strong></p>#', $result);
        $this->assertRegExp('#Name#', $result);
        $this->assertRegExp('#26 окт. 2016 г.#', $result);
        $this->assertRegExp('#Text#', $result);
        $this->assertRegExp('#<form id="add-comment-form"#', $result);
        $this->assertRegExp('#<input type="text"#', $result);
        $this->assertRegExp('#<textarea#', $result);
        $this->assertRegExp('#<input type="submit" value="Отправить">#', $result);
    }
}
